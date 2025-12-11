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

    <h2>{{ post.title }}</h2>
    <p class="author">Автор: {{ post.author.name }}</p>
    <p class="desc">{{ post.description }}</p>

    <!-- ❤️ Лайк -->
    <button class="like-btn" @click="likePost">
      ❤️ {{ post.likes.count }}
    </button>

    <!-- 💬 Коментарі -->
    <div class="comments">
      <h3>Коментарі</h3>

      <div v-if="post.comments.length === 0">
        Немає коментарів
      </div>

      <div v-for="c in post.comments" :key="c.id" class="comment">
        <b>{{ c.author }}</b>
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
    }
  },
  async mounted() {
    const userStr = localStorage.getItem('user')
    if (userStr) {
      try {
        const user = JSON.parse(userStr)
        // Mongo може зберігати _id, але на фронті ти могла зберегти id / _id / user_id
        this.userId = user._id || user.id || user.user_id || null
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
        // страховка: щоб не було undefined
        if (!data.likes) data.likes = { count: 0 }
        if (!data.comments) data.comments = []
        this.post = data
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
    this.$router.push('/catalog')  
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
        // ✅ тут ми вже точно беремо likes_count з бекенда
        this.post.likes.count = data.likes_count
      } catch (e) {
        console.error(e)
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

/* ---------- ФОТО (МЕНШЕ) ---------- */

.image {
  display: block;
  width: 100%;
  max-width: 520px;        /* 🔥 реально менша */
  max-height: 420px;       /* 🔥 обмеження по висоті */
  margin: 0 auto 28px;     /* центруємо */
  object-fit: contain;
  background: #f4f4f5;
  border-radius: 20px;
  padding: 12px;
}

/* ---------- ЗАГОЛОВКИ ---------- */

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
  text-align: left;
}

.desc {
  max-width: 640px;
  margin-bottom: 28px;
  font-size: 16px;
  line-height: 1.7;
  color: #374151;
  text-align: left;
}


/* ---------- ЛАЙК ---------- */

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

/* ---------- КОМЕНТАРІ ---------- */

.comments {
  display: flex;
  flex-direction: column;
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

/* ---------- ФОРМА ---------- */

textarea {
  width: 95%;
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


/* ---------- КНОПКА ---------- */

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


/* ---------- СТАНИ ---------- */

.center {
  text-align: center;
  margin-top: 80px;
  font-size: 16px;
  color: #6b7280;
}
/* ---------- Назад ---------- */
.back-btn {
  background: #ffffff;
  border: none;
  padding: 10px 18px;
  margin-bottom: 22px;
  font-size: 15px;
  font-weight: 500;
  color: #727680ff;
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


/* ---------- МОБІЛКА ---------- */

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

</style>
