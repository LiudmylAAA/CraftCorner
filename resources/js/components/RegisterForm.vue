<template>
  <form @submit.prevent="submitRegister">
    <input type="text" v-model="name" placeholder="Ім'я" required class="input" />
    <input type="email" v-model="email" placeholder="Email" required class="input" />
    <input type="password" v-model="password" placeholder="Пароль" required class="input" />
    <button type="submit" class="btn-submit">Зареєструватися</button>
  </form>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return { name:'', email:'', password:'' };
  },
  methods: {
    async submitRegister() {
      try {
        const res = await axios.post('/api/register', {
          name: this.name,
          email: this.email,
          password: this.password
        });

        // Викликаємо подію у Header/App.vue
        this.$emit('register-success', res.data.user);

        // Зберігаємо користувача в localStorage
        localStorage.setItem('user', JSON.stringify(res.data.user));

        // Перехід на Dashboard
        this.$router.push('/dashboard');

      } catch(e) {
        alert(e.response?.data?.message || 'Помилка реєстрації');
      }
    }
  }
};
</script>

<style scoped>
.input { display:block; width:100%; margin-bottom:10px; padding:8px; }
.btn-submit { background:#1e3a8a; color:white; width:100%; padding:8px; border:none; border-radius:5px; cursor:pointer; }
</style>
