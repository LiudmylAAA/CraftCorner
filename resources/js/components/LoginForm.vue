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
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    async submitLogin() {
      try {
        const res = await axios.post('/api/login', {
          email: this.email,
          password: this.password
        });

        // Зберігаємо користувача в localStorage
        localStorage.setItem('user', JSON.stringify(res.data.user));

        // Відправляємо дані в Header/App.vue
        this.$emit('login-success', res.data.user);

        // Перехід на Dashboard
        this.$router.push('/dashboard');

      } catch (e) {
        alert(e.response?.data?.message || 'Помилка авторизації');
      }
    }
  }
};
</script>

<style scoped>

.input {
  width: 90%;
  padding: 14px 16px;
  margin-bottom: 12px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  font-size: 15px;
  outline: none;
  background: #fafafa;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.input:focus {
  border-color: #5f87bfff;
  box-shadow: 0 0 0 1px #5f87bfff;
}

/* кнопка — як кнопка коментаря */
.btn-submit {
  width: 100%;
  margin-top: 8px;
  padding: 10px 24px;
  border-radius: 8px;
  border: none;
  background: linear-gradient(135deg, #658dcfff, #5f7fc5ff);
  color: #ffffff;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-submit:hover {
  transform: translateY(-1px);
  box-shadow: 0 14px 30px rgba(59, 130, 246, 0.45);
}

</style>
