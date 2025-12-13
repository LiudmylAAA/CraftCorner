<template>
  <div class="home">
    <!-- HERO / БАНЕР -->
    <section class="hero">
      <div class="hero-content">
        <h1 class="hero-title">
          CraftCorner — простір для творчості
        </h1>

        <p class="hero-subtitle">
          Діліться своїми роботами, знаходьте натхнення та спілкуйтесь з однодумцями 💙
        </p>

        <div class="hero-actions">
          <router-link to="/catalog" class="btn-primary">
            Перейти до каталогу
          </router-link>

          <router-link v-if="!user" to="/" class="btn-outline" @click.native.prevent>
            Увійти / Реєстрація
          </router-link>
        </div>
      </div>

      <!-- Декор -->
      <div class="hero-glow"></div>
    </section>

    <!-- МІНІ БЛОКИ (можеш залишити або прибрати) -->
    <section class="features">
      <div class="feature">
        <div class="icon">🎨</div>
        <div>
          <h3>Публікуй роботи</h3>
          <p>Завантажуй фото і описуй свою творчість.</p>
        </div>
      </div>

      <div class="feature">
        <div class="icon">❤️</div>
        <div>
          <h3>Отримуй лайки</h3>
          <p>Підтримуй інших і збирай реакції.</p>
        </div>
      </div>

      <div class="feature">
        <div class="icon">💬</div>
        <div>
          <h3>Коментуй</h3>
          <p>Спілкуйся та отримуй відгуки.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: "MainPage",
  computed: {
    user() {
      const u = localStorage.getItem("user")
      return u ? JSON.parse(u) : null
    },
  },
}
</script>

<style scoped>
.home {
  min-height: 100vh;
  padding: 30px 18px 60px;
  background: linear-gradient(135deg, #e8f6ff, #f7fbff);
}

/* ===== HERO / БАНЕР ===== */
.hero {
  position: relative;
  overflow: hidden;
  max-width: 1100px;
  margin: 18px auto 26px;
  padding: 46px 34px;
  border-radius: 26px;
  background: linear-gradient(135deg, rgba(101, 141, 207, 0.18), rgba(95, 127, 197, 0.12));
  border: 1px solid rgba(207, 232, 255, 0.8);
  box-shadow: 0 30px 90px rgba(2, 132, 199, 0.15);
}

.hero-content {
  position: relative;
  z-index: 2;
  max-width: 720px;
}

.hero-title {
  margin: 0 0 10px;
  font-size: 40px;
  line-height: 1.15;
  font-weight: 900;
  color: #0f172a;
}

.hero-subtitle {
  margin: 0 0 22px;
  font-size: 16px;
  line-height: 1.7;
  color: #334155;
  max-width: 560px;
}

/* кнопки */
.hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: center;
}

.btn-primary {
  text-decoration: none;
  padding: 11px 18px;
  border-radius: 999px;
  background: linear-gradient(135deg, #658dcf, #5f7fc5);
  color: #ffffff;
  font-weight: 600;
  font-size: 14px;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 14px 30px rgba(59, 130, 246, 0.35);
}

.btn-outline {
  text-decoration: none;
  padding: 11px 18px;
  border-radius: 999px;
  border: 1px solid rgba(101, 141, 207, 0.55);
  background: rgba(255, 255, 255, 0.7);
  color: #1e40af;
  font-weight: 600;
  font-size: 14px;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-outline:hover {
  transform: translateY(-1px);
  box-shadow: 0 14px 30px rgba(2, 132, 199, 0.12);
}

/* легкий glow декор */
.hero-glow {
  position: absolute;
  inset: -40%;
  background: radial-gradient(circle at 30% 30%, rgba(56, 189, 248, 0.25), transparent 55%),
              radial-gradient(circle at 70% 60%, rgba(101, 141, 207, 0.22), transparent 58%);
  filter: blur(10px);
  z-index: 1;
}

/* ===== FEATURES ===== */
.features {
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
}

.feature {
  background: rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(226, 232, 240, 0.9);
  border-radius: 18px;
  padding: 16px;
  display: flex;
  gap: 12px;
  align-items: flex-start;
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
}

.icon {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  background: #e8f6ff;
  display: grid;
  place-items: center;
  font-size: 20px;
}

.feature h3 {
  margin: 0 0 4px;
  font-size: 16px;
  font-weight: 800;
  color: #0f172a;
}

.feature p {
  margin: 0;
  font-size: 14px;
  color: #475569;
  line-height: 1.6;
}

@media (max-width: 900px) {
  .hero-title { font-size: 32px; }
  .features { grid-template-columns: 1fr; }
}
</style>
