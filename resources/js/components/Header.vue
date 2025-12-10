<template>
  <header class="header">
    <h1 class="logo">CraftCorner</h1>
    <div class="menu">
      <router-link to="/">Головна</router-link>
      <router-link to="/catalog">Каталог</router-link>
      <router-link to="/about">Про нас</router-link>
    </div>

    <div v-if="user">
      <router-link to="/dashboard" class="btn-auth">Мій кабінет</router-link>
      <button @click="logout" class="btn-logout">Вийти</button>
    </div>
    <button v-else @click="showModal = true" class="btn-auth">Увійти / Реєстрація</button>

    <div v-if="showModal" class="modal-backdrop" @click.self="showModal = false">
      <div class="modal">
        <div class="tabs">
          <button :class="{active: tab==='login'}" @click="tab='login'">Вхід</button>
          <button :class="{active: tab==='register'}" @click="tab='register'">Реєстрація</button>
        </div>
        <div class="tab-content">
          <LoginForm v-if="tab==='login'" @login-success="onLoginSuccess" />
          <RegisterForm v-if="tab==='register'" @register-success="onLoginSuccess" />
        </div>
        <button class="modal-close" @click="showModal = false">✕</button>
      </div>
    </div>
  </header>
</template>

<script>
import LoginForm from './LoginForm.vue';
import RegisterForm from './RegisterForm.vue';

export default {
  props: ['user'],
  components: { LoginForm, RegisterForm },
  data() {
    return { showModal: false, tab: 'login' };
  },
  methods: {
    onLoginSuccess(userData) {
      this.$emit('login', userData);
      localStorage.setItem('user', JSON.stringify(userData));
      this.showModal = false;
    },
    logout() {
      localStorage.removeItem('user');
      this.$emit('login', null);
    }
  }
};
</script>

<style scoped>
.header { background: #cce0ff; padding: 10px 20px; display:flex; justify-content:space-between; align-items:center; }
.menu a { margin: 0 10px; color:#1e3a8a; text-decoration:none; }
.menu a:hover { text-decoration:underline; }
.btn-auth { background: #1e3a8a; color:white; border:none; padding:8px 16px; border-radius:5px; cursor:pointer; }
.modal-backdrop { position:fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; }
.modal { background:white; padding:20px; border-radius:10px; width:300px; position:relative; }
.tabs button { padding:8px 12px; cursor:pointer; border:none; background:#ddd; margin-right:5px; border-radius:5px 5px 0 0; }
.tabs button.active { background:#1e3a8a; color:white; }
.modal-close { position:absolute; top:5px; right:5px; border:none; background:transparent; font-size:18px; cursor:pointer; }
</style>
