<template>
  <div class="catalog">
    <h2 class="title">Каталог робіт</h2>

    <div class="search-box">
      <input
        v-model="search"
        type="text"
        placeholder="Пошук за назвою або описом..."
      />
    </div>

    <div v-if="loading" class="center">Завантаження...</div>

    <div v-else-if="posts.length === 0" class="center">
      Поки що немає робіт
    </div>

    <div v-else-if="filteredPosts.length === 0" class="center">
      Нічого не знайдено
    </div>

    <div v-else class="masonry">
      <div
        class="card"
        v-for="post in filteredPosts"
        :key="post.id"
        @click="openPost(post.id)"
      >
        <img :src="post.image_url" :alt="post.title" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Catalog',
  data() {
    return {
      posts: [],
      loading: false,
      search: '', 
    }
  },
  computed: {
    filteredPosts() {
      if (!this.search.trim()) return this.posts

      const q = this.search.toLowerCase()

      return this.posts.filter((post) => {
        const title = post.title ? post.title.toLowerCase() : ''
        const desc = post.description ? post.description.toLowerCase() : ''
        return title.includes(q) || desc.includes(q)
      })
    },
  },
  methods: {
    async loadPosts() {
      this.loading = true
      try {
        const res = await fetch('/api/posts')
        this.posts = await res.json()
      } catch (e) {
        console.error('Помилка завантаження постів', e)
      } finally {
        this.loading = false
      }
    },
    openPost(id) {
      this.$router.push(`/posts/${id}`)
    },
  },
  mounted() {
    this.loadPosts()
  },
}
</script>

<style scoped>
.catalog {
  padding: 20px;
}

.title {
font-size: 30px;
line-height: 2;
  font-weight: 700;
  margin: 0;
  text-align: center;
  background: linear-gradient(90deg,#0284c7,#38bdf8);
  -webkit-background-clip: text;
  color: transparent;
}

.search-box {
  max-width: 700px;
  margin: 0 auto 28px;
}

.search-box input {
  width: 100%;
  padding: 12px 18px;
  border-radius: 999px;
  border: 1px solid #d1d5db;
  font-size: 15px;
  outline: none;
  background: #ffffff;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.search-box input:focus {
  border-color: #5f87bf;
  box-shadow: 0 0 0 2px rgba(95, 135, 191, 0.25);
}



.masonry {
  column-count: 5;
  column-gap: 16px;
}

.card {
  break-inside: avoid;
  background: #fff;
  margin-bottom: 16px;
  border-radius: 10px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: 0.2s;
}

.card:hover {
  transform: translateY(-4px);
}

.card img {
  width: 100%;
  display: block;
}


.center {
  text-align: center;
  margin-top: 40px;
}

/* Адаптив */

@media (max-width: 1200px) {
  .masonry {
    column-count: 3;
  }
}
@media (max-width: 800px) {
  .masonry {
    column-count: 2;
  }
}
@media (max-width: 500px) {
  .masonry {
    column-count: 1;
  }
}
</style>
