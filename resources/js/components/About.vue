<template>
  <div class="about">
    <header class="hero">
      <h2 class="title">Про CraftCorner</h2>
      <p class="subtitle">
        Платформа для творчих людей: діліться роботами, надихайтесь і спілкуйтесь 💙
      </p>
    </header>

    <!-- Інтерактивні блоки (акордеон) -->
    <section class="blocks">
      <div
        class="block"
        v-for="(b, i) in blocks"
        :key="b.title"
        :class="{ open: openIndex === i }"
        @click="toggle(i)"
      >
        <div class="block-head">
          <div class="block-icon">{{ b.icon }}</div>
          <div class="block-title">{{ b.title }}</div>
          <div class="chev">{{ openIndex === i ? '—' : '+' }}</div>
        </div>

        <div class="block-body" v-show="openIndex === i">
          <p class="text">{{ b.text }}</p>
          <ul class="list">
            <li v-for="(item, k) in b.items" :key="k">{{ item }}</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="faq">
      <h3 class="section-title">Питання та відповіді</h3>

      <div
        class="faq-item"
        v-for="(f, i) in faq"
        :key="f.q"
      >
        <button class="faq-q" @click="toggleFaq(i)">
          {{ f.q }}
          <span class="faq-plus">{{ faqOpen === i ? '—' : '+' }}</span>
        </button>

        <div class="faq-a" v-show="faqOpen === i">
          {{ f.a }}
        </div>
      </div>
    </section>
    <!-- Про розробника -->
<section class="developer">
  <h3 class="section-title">Про розробника</h3>

  <div class="developer-card">
    <div class="developer-avatar">
      👩‍💻
    </div>

    <div class="developer-info">
      <p class="developer-name">Людмила</p>
      <p class="developer-role">
        Розробниця вебзастосунку CraftCorner
      </p>

      <p class="developer-text">
        CraftCorner — це навчальний проєкт, створений у процесі вивчення
        веброзробки. Метою було створити зручну та естетичну платформу
        для творчих людей, де можна ділитися роботами, отримувати відгуки
        та надихати інших.
      </p>

      <p class="developer-text">
        Проєкт реалізовано з використанням <b>Vue.js</b>, <b>Laravel</b> та <b>MongoDB</b>.
      </p>
    </div>
  </div>
</section>

  </div>
</template>

<script>
export default {
  name: 'About',
  data() {
    return {
      openIndex: 0,
      faqOpen: null,

      // Можеш поставити реальні цифри (або підтягнути з API)
      target: { posts: 120, likes: 540, comments: 210 },
      animated: { posts: 0, likes: 0, comments: 0 },

      blocks: [
        {
          icon: '🎨',
          title: 'Про платформу',
          text:
            'CraftCorner — це місце для рукоділля та творчості, де можна ділитись роботами та отримувати підтримку.',
          items: [
            'публікація робіт з фото',
            'каталог робіт',
            'лайки та коментарі',
          ],
        },
        {
          icon: '🧵',
          title: 'Для кого цей сайт',
          text:
            'Платформа підходить і для професіоналів, і для новачків — головне, щоб була любов до творчості.',
          items: [
            'майстри та рукодільники',
            'початківці',
            'усі, хто любить натхнення',
          ],
        },
        {
          icon: '✅',
          title: 'Що можна робити',
          text:
            'Все максимально просто: опублікували роботу — отримали фідбек — надихнули інших.',
          items: [
            'публікувати власні роботи',
            'переглядати каталог',
            'лайкати та коментувати',

          ],
        },
      ],

      faq: [
        {
          q: 'Чи потрібно реєструватись, щоб переглядати каталог?',
          a: 'Ні, каталог можна переглядати без реєстрації. Але для лайків та коментарів — потрібен акаунт.',
        },
        {
          q: 'Чи можна редагувати або видаляти свої роботи?',
          a: 'Так, автор може редагувати та видаляти власні пости у деталях поста.',
        },
        {
          q: 'Які роботи можна публікувати?',
          a: 'Будь-які творчі роботи: рукоділля, орнаменти, вироби, хендмейд-проєкти тощо.',
        },
      ],
    }
  },
  mounted() {
    this.animateNumbers()
  },
  methods: {
    toggle(i) {
      this.openIndex = this.openIndex === i ? null : i
    },
    toggleFaq(i) {
      this.faqOpen = this.faqOpen === i ? null : i
    },
    animateNumbers() {
      const duration = 900
      const start = performance.now()
      const from = { posts: 0, likes: 0, comments: 0 }
      const to = this.target

      const step = (t) => {
        const p = Math.min(1, (t - start) / duration)
        this.animated.posts = Math.floor(from.posts + (to.posts - from.posts) * p)
        this.animated.likes = Math.floor(from.likes + (to.likes - from.likes) * p)
        this.animated.comments = Math.floor(from.comments + (to.comments - from.comments) * p)

        if (p < 1) requestAnimationFrame(step)
      }

      requestAnimationFrame(step)
    },
  },
}
</script>

<style scoped>
.about {
  max-width: 900px;
  margin: 60px auto;
  padding: 32px;
  background: #ffffff;
  border-radius: 24px;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.08);
}

.hero {
  text-align: center;
  margin-bottom: 26px;
}

.title {
  font-size: 32px;
  font-weight: 800;
  margin: 0 0 10px;
  background: linear-gradient(90deg, #0284c7, #38bdf8);
  -webkit-background-clip: text;
  color: transparent;
}

.subtitle {
  margin: 0;
  color: #475569;
  font-size: 16px;
  line-height: 1.6;
}

/* stats */
.stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin: 22px 0 28px;
}

.stat-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 16px;
  text-align: center;
}

.stat-num {
  font-size: 26px;
  font-weight: 800;
  color: #0f172a;
}

.stat-label {
  margin-top: 6px;
  font-size: 13px;
  color: #64748b;
}

/* blocks */
.blocks {
  display: grid;
  gap: 12px;
  margin-top: 10px;
}

.block {
  border: 1px solid #e2e8f0;
  background: #ffffff;
  border-radius: 16px;
  padding: 14px 16px;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.block:hover {
  transform: translateY(-1px);
  box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
}

.block.open {
  border-color: #cfe8ff;
  box-shadow: 0 16px 40px rgba(2, 132, 199, 0.12);
}

.block-head {
  display: flex;
  align-items: center;
  gap: 12px;
}

.block-icon {
  width: 36px;
  height: 36px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  background: #e8f6ff;
  font-size: 18px;
}

.block-title {
  font-weight: 700;
  color: #0f172a;
  flex: 1;
}

.chev {
  font-size: 22px;
  color: #64748b;
}

.block-body {
  margin-top: 12px;
}

.text {
  color: #334155;
  line-height: 1.7;
  margin: 0 0 10px;
}

.list {
  margin: 0;
  padding-left: 18px;
  color: #334155;
}

.list li {
  margin-bottom: 6px;
}

/* FAQ */
.faq {
  margin-top: 30px;
}

.section-title {
  font-size: 20px;
  font-weight: 800;
  margin: 0 0 14px;
  color: #0f172a;
}

.faq-item {
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  margin-bottom: 10px;
  overflow: hidden;
}

.faq-q {
  width: 100%;
  text-align: left;
  background: #f8fafc;
  border: none;
  padding: 14px 16px;
  cursor: pointer;
  font-weight: 700;
  color: #0f172a;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.faq-plus {
  color: #64748b;
  font-size: 20px;
}

.faq-a {
  padding: 12px 16px 16px;
  color: #334155;
  line-height: 1.7;
}

/* mobile */
@media (max-width: 820px) {
  .stats {
    grid-template-columns: 1fr;
  }
}
/* ===== Про розробника ===== */

.developer {
  margin-top: 40px;
}

.developer-card {
  display: flex;
  gap: 16px;
  padding: 18px;
  border-radius: 18px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
}

.developer-avatar {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: #e8f6ff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
}

.developer-info {
  flex: 1;
}

.developer-name {
  font-size: 18px;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}

.developer-role {
  font-size: 13px;
  color: #64748b;
  margin: 4px 0 10px;
}

.developer-text {
  font-size: 15px;
  line-height: 1.7;
  color: #334155;
  margin-bottom: 8px;
}


@media (max-width: 600px) {
  .developer-card {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
}

</style>
