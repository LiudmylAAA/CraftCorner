<template>
  <div class="dashboard">
    <div class="dashboard-header">
      <h2 class="dashboard-title">Привіт {{ user?.name }}!</h2>

      <div class="user-avatar-wrapper" ref="avatarWrapper">
        <img :src="user?.avatar || '/default-avatar.png'" alt="avatar" class="user-avatar">
        <button class="avatar-settings-btn" @click="toggleAvatarMenu">⚙️</button>

        <div v-if="showAvatarMenu" class="avatar-menu">
          <label class="avatar-btn">
            змінити фото
            <input type="file" accept="image/*" @change="changeAvatar">
          </label>
        </div>
      </div>
    </div>

    <section class="user-info card">
      <p><strong>Email:</strong> {{ user?.email }}</p>
    </section>

    <section class="add-work card">
      <h3>Додати нову роботу</h3>
      <form @submit.prevent="submitWork" enctype="multipart/form-data">
        <input type="text" v-model="newWork.title" placeholder="Назва роботи" required />
        <textarea v-model="newWork.description" placeholder="Опис роботи"></textarea>

        <label class="file-label">
          Вибрати фото
          <input type="file" @change="onFileChange" accept="image/*" />
        </label>

        <div v-if="preview" class="preview">
          <img :src="preview" alt="preview" />
        </div>

        <button type="submit" class="btn-primary">Додати</button>
      </form>
    </section>

    <section class="user-works">
      <h3>Мої роботи</h3>
      <div v-if="works.length === 0" class="no-works">Немає робіт</div>

      <div class="works-grid">
        <div
          v-for="work in works"
          :key="work._id || work.id"
          class="work-card"
          @click="goToWork(work)"
        >
          <img :src="work.image_url" alt="work" />
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['user'],
  data() {
    return {
      newWork: { title: '', description: '' },
      file: null,
      preview: null,
      showAvatarMenu: false,
      works: []
    };
  },
  methods: {
    toggleAvatarMenu() {
      this.showAvatarMenu = !this.showAvatarMenu;
    },

    handleClickOutside(event) {
      const wrapper = this.$refs.avatarWrapper;
      if (wrapper && !wrapper.contains(event.target)) {
        this.showAvatarMenu = false;
      }
    },

    onFileChange(e) {
      const f = e.target.files[0];
      if (!f) {
        this.file = null;
        this.preview = null;
        return;
      }
      if (f.size > 5 * 1024 * 1024) {
        alert('Файл занадто великий (макс 5MB)');
        this.file = null;
        this.preview = null;
        return;
      }
      this.file = f;
      const reader = new FileReader();
      reader.onload = (ev) => (this.preview = ev.target.result);
      reader.readAsDataURL(f);
    },

    async submitWork() {
      if (!this.newWork.title) return alert('Введіть назву роботи');

      try {
        const formData = new FormData();
        formData.append('title', this.newWork.title);
        formData.append('description', this.newWork.description || '');
        const userId = this.user?._id || this.user?.id || this.user;
        formData.append('user_id', userId);
        if (this.file) formData.append('image', this.file);

        const res = await axios.post('/api/posts', formData);
        this.works.unshift(res.data);

        this.newWork.title = '';
        this.newWork.description = '';
        this.file = null;
        this.preview = null;
      } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || 'Помилка при додаванні роботи');
      }
    },

    async changeAvatar(e) {
      const file = e.target.files[0];
      if (!file) return;
      if (file.size > 5 * 1024 * 1024) {
        alert('Файл занадто великий (макс 5MB)');
        return;
      }

      const formData = new FormData();
      formData.append('avatar', file);

      try {
        const res = await axios.post(
          `/api/users/${this.user._id}/avatar`,
          formData,
          { headers: { 'Content-Type': 'multipart/form-data' } }
        );

        this.user.avatar = res.data.avatar;
        this.$emit('update-user', res.data);
      } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || 'Помилка при зміні аватара');
      }
    },

    async deleteAvatar() {
      if (!this.user?._id) return;
      if (!confirm('Видалити аватар?')) return;

      try {
        const res = await axios.delete(`/api/users/${this.user._id}/avatar`);
        this.user.avatar = null;
        this.showAvatarMenu = false;
        this.$emit('update-user', res.data);
        alert('Аватар видалено!');
      } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || 'Помилка при видаленні аватара');
      }
    },

    goToWork(work) {
    this.$router.push(`/posts/${work._id || work.id}?from=dashboard`);
    },


    async fetchWorks() {
      try {
        const res = await axios.get('/api/posts');
        const userId = this.user?._id || this.user?.id || this.user;

        this.works = (res.data || []).filter((p) => {
          const authorId = p.author?.id || p.user_id;
          return authorId && String(authorId) === String(userId);
        });
      } catch (e) {
        console.error('Помилка при завантаженні робіт', e);
      }
    }
  },

  mounted() {
    if (this.user) this.fetchWorks();
    document.addEventListener('click', this.handleClickOutside);
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  }
};
</script>


<style scoped>
.dashboard {
  min-height: 100vh;
  padding: 40px 20px;
  background: linear-gradient(135deg, #e8f6ff, #f7fbff);
  font-family: 'Inter', Arial, sans-serif;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 25px;
}

.dashboard-title {
  font-size: 30px;
  font-weight: 700;
  margin: 0;
  text-align: center;
  background: linear-gradient(90deg,#0284c7,#38bdf8);
  -webkit-background-clip: text;
  color: transparent;
}

.card {
  width: 100%;
  max-width: 800px;
  background: rgba(255,255,255,0.7);
  backdrop-filter: blur(8px);
  padding: 20px;
  border-radius: 14px;
}

input, textarea {
  width: 100%;
  padding: 12px;
  background: #f0f9ff;
  border: 1px solid #d7f0ff;
  border-radius: 10px;
  outline: none;
  margin-bottom: 12px;
  font-size: 15px;
}

.file-label { display: inline-block; margin: 8px 0; cursor: pointer; color: #1e3a8a; }

.preview img { max-width: 320px; border-radius: 12px; }

.works-grid {
  column-count: 5;
  column-gap: 12px;
  width: 100%;
}

@media (max-width: 1200px) { .works-grid { column-count: 4; } }
@media (max-width: 900px) { .works-grid { column-count: 3; } }
@media (max-width: 600px) { .works-grid { column-count: 2; } }
@media (max-width: 400px) { .works-grid { column-count: 1; } }

.work-card {
  display: inline-block;
  width: 100%;
  margin: 0 0 12px;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  break-inside: avoid;
  transition: transform 0.2s, box-shadow 0.2s;
}

.work-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.work-card img {
  width: 100%;
  height: auto;
  display: block;
  border-radius: 12px;
}

.dashboard-header {
  width: 100%;
  max-width: 800px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.user-avatar-wrapper { position: relative; display: inline-block; }
.user-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; cursor: pointer; }

.avatar-menu {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  padding: 8px;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  z-index: 50;
  min-width: 140px;
}

.avatar-btn {
  padding: 6px 10px;
  border-radius: 8px;
  background: #38bdf8;
  color: white;
  cursor: pointer;
  font-size: 14px;
  text-align: center;
  border: none;
  position: relative;
  overflow: hidden;
}

.avatar-btn input { opacity: 0; position: absolute; inset: 0; cursor: pointer; }
.avatar-btn.delete { background: #ef4444; }

.avatar-settings-btn {
  position: absolute;
  bottom: 0;
  right: 0;
  background: #38bdf8;
  border: none;
  color: white;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-primary {
  width: 100%;
  padding: 12px;
  background: linear-gradient(90deg, #0ea5e9, #38bdf8);
  color: white;
  font-weight: 600;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  transition: .2s;
  margin-top: 10px;
  font-size: 16px;
}
.btn-primary:hover {
  background: linear-gradient(90deg, #0284c7, #0ea5e9);
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(3, 105, 161, 0.25);
}

</style>
