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

.input {
  width: 90%;
  padding: 14px 16px;
  margin-bottom: 12px;
  border-radius: 8px;              
  border: 1px solid #d1d5db;
  font-size: 15px;
  background: #fafafa;
  outline: none;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.input:focus {
  border-color: #5f87bfff;
  box-shadow: 0 0 0 1px #5f87bfff;
}

.btn-submit {
  width: 100%;
  margin-top: 6px;
  padding: 12px;
  border-radius: 8px;           
  border: none;
  background: linear-gradient(135deg, #658dcfff, #5f7fc5ff);
  color: #ffffff;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.btn-submit:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 24px rgba(59, 130, 246, 0.35);
}
</style>
