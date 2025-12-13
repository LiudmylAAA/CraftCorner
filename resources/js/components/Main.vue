<template>
  <div>
    <app-header />
    <main class="main-content">
      <h1>Ласкаво просимо на CraftCorner!</h1>
      <p>Тут можна додати будь-який контент для головної сторінки.</p>
    </main>
    <app-footer />
  </div>
</template>

<script>
import AppHeader from './Header.vue';
import AppFooter from './Footer.vue';

export default {
  components: {
    'app-header': AppHeader,
    'app-footer': AppFooter
  }
};
</script>

<style scoped>
.main-content {
  padding: 50px 20px;
  text-align: center;
  background: #ffffffff; 
}
.main-content h1 {
  color: #1e3a8a; 
  margin-bottom: 15px;
}
.main-content p {
  font-size: 18px;
  color: #1e40af; 
}
</style>
