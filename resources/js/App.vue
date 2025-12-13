<template>
  <div>
    <app-header :user="user" @login="setUser" />
    <router-view :user="user" />
    <app-footer />
  </div>
</template>

<script>
import AppHeader from './components/Header.vue';
import AppFooter from './components/Footer.vue';

export default {
  components: { 'app-header': AppHeader, 'app-footer': AppFooter },
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')) || null
    };
  },
  methods: {
    setUser(userData) {
      this.user = userData;
    }
  }
};
</script>

<style scoped>
/* базові стилі */
</style>
