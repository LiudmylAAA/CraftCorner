<template>
  <div v-if="loading" class="center">Завантаження...</div>

  <div v-else-if="!post" class="center">
    Робота не знайдена
  </div>

  <div class="post" v-else>

    <button class="back-btn" @click="goBack">
      ← Назад 
    </button>

    <img :src="post.image_url" class="image" />

  
    <div v-if="!isEditing">
      <h2>{{ post.title }}</h2>
      <p class="author">Автор: {{ post.author.name }}</p>
      <p class="desc">{{ post.description }}</p>
    </div>


    <div v-else class="edit-block">
      <input
        v-model="editTitle"
        type="text"
        class="edit-input"
        placeholder="Назва роботи"
      />
      <textarea
        v-model="editDescription"
        class="edit-textarea"
        placeholder="Опис роботи"
      ></textarea>
    </div>


    <div v-if="isOwner" class="owner-actions">
      <template v-if="!isEditing">
        <button class="owner-btn edit" @click="startEdit">Редагувати</button>
        <button class="owner-btn delete" @click="deletePost">Видалити</button>
      </template>
      <template v-else>
        <button class="owner-btn cancel" @click="cancelEdit">Скасувати</button>
        <button class="owner-btn save" @click="saveEdit">Зберегти</button>
      </template>
    </div>


    <button class="like-btn" @click="likePost">
      ❤️ {{ post.likes.count }}
    </button>


    <div class="comments">
      <h3>Коментарі</h3>

      <div v-if="post.comments.length === 0">
        Немає коментарів
      </div>

    <div v-for="c in post.comments" :key="c.id" class="comment">
    <div class="comment-header">
        <b>{{ c.author }}</b>

        <!--  кнопка тільки для адміна -->
        <button
        v-if="isAdmin"
        class="comment-delete-btn"
        @click.stop="deleteComment(c.id)"
        >
        Видалити
        </button>
    </div>

    <p>{{ c.text }}</p>
    </div>


      <textarea
        v-model="comment"
        placeholder="Написати коментар..."
      ></textarea>
      <button @click="sendComment">Надіслати</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PostDetails',
  data() {
    return {
      post: null,
      comment: '',
      userId: null,
      loading: false,
      isOwner: false,
      isEditing: false,
      editTitle: '',
      editDescription: '',
      fromPage: null,
      isAdmin: false,
    }
  },
  async mounted() {
    this.fromPage = this.$route.query.from || null;
    const userStr = localStorage.getItem('user')
    if (userStr) {
      try {
        const user = JSON.parse(userStr)
        this.userId = user._id || user.id || user.user_id || null
         this.isAdmin = user.email === 'admin@gmail.com'
      } catch (e) {
        console.error('Помилка розбору user з localStorage', e)
      }
    }

    this.loading = true
    try {
      const res = await fetch(`/api/posts/${this.$route.params.id}`)
      if (!res.ok) {
        console.error('Помилка завантаження поста', await res.text())
        this.post = null
      } else {
        const data = await res.json()
        if (!data.likes) data.likes = { count: 0 }
        if (!data.comments) data.comments = []
        this.post = data


        const authorId = data.author?.id
        this.isOwner =
          !!this.userId && authorId && String(authorId) === String(this.userId)
      }
    } catch (e) {
      console.error(e)
      this.post = null
    } finally {
      this.loading = false
    }
  },
  methods: {
   goBack() {
  if (this.fromPage === 'catalog') {
    this.$router.push('/catalog');
  } 
  else if (this.fromPage === 'dashboard') {
    this.$router.push('/dashboard');
  } 
  else {
    this.$router.push('/catalog'); 
  }
   },

    async likePost() {
      if (!this.userId) {
        alert('Увійдіть, щоб ставити лайки')
        return
      }

      try {
        const res = await fetch(`/api/posts/${this.post.id}/like`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: this.userId }),
        })

        if (!res.ok) {
          console.error('Помилка лайка', await res.text())
          return
        }

        const data = await res.json()
        this.post.likes.count = data.likes_count
      } catch (e) {
        console.error(e)
      }
    },

async deleteComment(commentId) {
  if (!this.isAdmin) return // на всякий випадок

  if (!this.userId) {
    alert('Увійдіть, щоб видаляти коментарі')
    return
  }

  if (!confirm('Видалити цей коментар?')) return

  try {
    const res = await fetch(`/api/comments/${commentId}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user_id: this.userId }),
    })

    if (!res.ok) {
      console.error('Помилка видалення коментаря', await res.text())
      alert('Не вдалося видалити коментар')
      return
    }

    // видаляємо з масиву на фронті
    this.post.comments = this.post.comments.filter(c => c.id !== commentId)
  } catch (e) {
    console.error(e)
    alert('Сталася помилка при видаленні')
  }
},

    async sendComment() {
      if (!this.userId) {
        alert('Увійдіть, щоб писати коментарі')
        return
      }

      if (!this.comment.trim()) return

      try {
        const res = await fetch(`/api/posts/${this.post.id}/comments`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            user_id: this.userId,
            text: this.comment,
          }),
        })

        if (!res.ok) {
          console.error('Помилка коментаря', await res.text())
          return
        }

        const newComment = await res.json()
        this.post.comments.push(newComment)
        this.comment = ''
      } catch (e) {
        console.error(e)
      }
    },


    startEdit() {
      this.isEditing = true
      this.editTitle = this.post.title
      this.editDescription = this.post.description
    },

    cancelEdit() {
      this.isEditing = false
      this.editTitle = ''
      this.editDescription = ''
    },

    async saveEdit() {
      if (!this.userId) {
        alert('Увійдіть, щоб редагувати роботу')
        return
      }

      if (!this.editTitle.trim()) {
        alert('Введіть назву роботи')
        return
      }

      try {
        const res = await fetch(`/api/posts/${this.post.id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            title: this.editTitle,
            description: this.editDescription,
            user_id: this.userId, 
          }),
        })

        if (!res.ok) {
          console.error('Помилка редагування', await res.text())
          return
        }

        const updated = await res.json()
        this.post.title = updated.title
        this.post.description = updated.description
        this.isEditing = false
      } catch (e) {
        console.error(e)
      }
    },

    async deletePost() {
      if (!this.userId) {
        alert('Увійдіть, щоб видаляти роботу')
        return
      }

      if (!confirm('Точно видалити цю роботу?')) return

      try {
        const res = await fetch(`/api/posts/${this.post.id}`, {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ user_id: this.userId }), 
        })

        if (!res.ok) {
          console.error('Помилка видалення', await res.text())
          return
        }

        alert('Роботу видалено')
        this.$router.push('/catalog')
      } catch (e) {
        console.error(e)
      }
    },
  },
}
</script>

<style scoped>
.post {
  max-width: 860px;
  margin: 48px auto;
  padding: 32px;
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.08);
}


.back-btn {
  background: #ffffff;
  border: none;
  padding: 10px 18px;
  margin-bottom: 22px;
  font-size: 15px;
  font-weight: 500;
  color: #686c75ff;
  border-radius: 999px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.back-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
}


.image {
  display: block;
  width: 100%;
  max-width: 520px;
  max-height: 420px;
  margin: 0 auto 28px;
  object-fit: contain;
  background: #f4f4f5;
  border-radius: 20px;
  padding: 12px;
}


h2 {
  font-size: 26px;
  font-weight: 600;
  color: #111827;
  margin-bottom: 6px;
  text-align: center;
}

.author {
  font-size: 14px;
  color: #6b7280;
  margin-bottom: 18px;
}

.desc {
  max-width: 640px;
  margin-bottom: 28px;
  font-size: 16px;
  line-height: 1.7;
  color: #374151;
}


.edit-block {
  max-width: 640px;
  margin-bottom: 20px;
}

.edit-input {
  width: 100%;
  padding: 10px 14px;
  border-radius: 12px;
  border: 1px solid #d1d5db;
  margin-bottom: 10px;
  font-size: 15px;
}

.edit-textarea {
  width: 100%;
  min-height: 90px;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid #d1d5db;
  font-size: 15px;
  resize: vertical;
}


.owner-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-bottom: 16px;
}

.owner-btn {
  padding: 8px 14px;
  border-radius: 999px;
  border: none;
  font-size: 14px;
  cursor: pointer;
  transition: 0.15s;
}

.owner-btn.edit {
  background: #e0f2fe;
  color: #075985;
}

.owner-btn.delete {
  background: #fee2e2;
  color: #b91c1c;
}

.owner-btn.save {
  background: #22c55e;
  color: #ffffff;
}

.owner-btn.cancel {
  background: #e5e7eb;
  color: #111827;
}

.owner-btn:hover {
  transform: translateY(-1px);
}

/* лайк */
.like-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0 auto 32px;
  padding: 10px 22px;
  border-radius: 999px;
  border: none;
  background: #ffe4e6;
  color: #be123c;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.like-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(248, 113, 113, 0.35);
}

/* коментарі */
.comments {
  max-width: 640px;
  margin: 0 auto;
}

.comments h3 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 20px;
  color: #111827;
}

.comment {
  padding: 14px 0;
  border-bottom: 1px solid #e5e7eb;
}

.comment:last-child {
  border-bottom: none;
}

.comment b {
  font-size: 14px;
  color: #111827;
}

.comment p {
  margin-top: 4px;
  font-size: 15px;
  color: #374151;
}

/* поле комента */
textarea {
  width: 100%;
  min-height: 90px;
  margin-top: 16px;
  padding: 14px 16px;
  border-radius: 16px;
  border: 1px solid #d1d5db;
  font-size: 15px;
  resize: vertical;
  outline: none;
  background: #fafafa;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

textarea:focus {
  border-color: #5f87bfff;
  box-shadow: 0 0 0 1px #5f87bfff;
}

/* кнопка комента */
.comments button {
  margin-left: auto;
  margin-top: 12px;
  padding: 10px 24px;
  border-radius: 999px;
  border: none;
  background: linear-gradient(135deg, #658dcfff, #5f7fc5ff);
  color: #ffffff;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.comments button:hover {
  transform: translateY(-1px);
  box-shadow: 0 14px 30px rgba(59, 130, 246, 0.45);
}

/* стани */
.center {
  text-align: center;
  margin-top: 80px;
  font-size: 16px;
  color: #6b7280;
}


@media (max-width: 768px) {
  .post {
    margin: 20px;
    padding: 20px;
  }

  .image {
    max-width: 100%;
    max-height: 300px;
  }

  h2 {
    font-size: 22px;
  }
}
.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.comment-delete-btn {
  all: unset !important;
  color: #dc2626 !important;
  font-size: 16px !important;
  font-weight: 600 !important;
  cursor: pointer !important;
}

.comment-delete-btn:hover {
  color: #b91c1c !important; /* тільки зміна кольору */
  text-decoration: none !important; /* 🔥 не підкреслює */
}



</style>
