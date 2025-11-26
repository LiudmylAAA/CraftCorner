<template>
  <form @submit.prevent="submitLogin">
    <input type="email" v-model="email" placeholder="Email" required class="input" />
    <input type="password" v-model="password" placeholder="Пароль" required class="input" />
    <button type="submit" class="btn-submit">Увійти</button>
  </form>
</template>

<script>
import axios from 'axios';
export default {
  data() { return { email:'', password:'' }; },
  methods: {
    async submitLogin() {
      try {
        const res = await axios.post('/api/login', { email: this.email, password: this.password });
        alert(res.data.message);
      } catch(e) { alert(e.response.data.message || 'Помилка'); }
    }
  }
};
</script>

<style scoped>
.input { display:block; width:100%; margin-bottom:10px; padding:8px; }
.btn-submit { background:#1e3a8a; color:white; width:100%; padding:8px; border:none; border-radius:5px; cursor:pointer; }
</style>
