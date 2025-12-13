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
.header {
  background: #e8f1ff;
  padding: 12px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* меню */
.menu a {
  margin: 0 10px;
  color: #5f7fc5;
  text-decoration: none;   
  font-weight: 500;
  transition: color 0.2s ease;
}

.menu a:hover {
  text-decoration: none;
}

.menu a.active,
.menu a.router-link-active {
  color: #ffffff;      
  font-weight: 600;
}


/* кнопка авторизації */
.btn-auth {
  background: linear-gradient(135deg, #658dcf, #5f7fc5);
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
   text-decoration: none; 
}

.btn-auth:hover {
  transform: translateY(-1px);
  
  box-shadow: 0 10px 22px rgba(59, 130, 246, 0.35);
}

/* затемнення */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

/* модалка */
.modal {
  background: white;
  padding: 22px;
  border-radius: 14px;
  width: 320px;
  position: relative;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
  z-index: 10000;
}

/* ТАБИ */
.tabs {
  display: flex;
  gap: 6px;
  margin-bottom: 14px;
}

.tabs button {
  flex: 1;
  padding: 10px 0;
  cursor: pointer;
  border: none;
  background: #e5edff;
  border-radius: 10px 10px 0 0;
  font-size: 14px;
  font-weight: 600;
  color: #1e3a8a;
  transition: background 0.2s ease, color 0.2s ease;
}

.tabs button.active {
  background: linear-gradient(135deg, #658dcf, #5f7fc5);
  color: white;
}

/* хрестик */
.modal-close {
  position: absolute;
  top: 8px;
  right: 8px;
  border: none;
  background: transparent;
  font-size: 18px;
  cursor: pointer;
  color: #6b7280;
}

.modal-close:hover {
  color: #1e3a8a;
}
.btn-logout {
  background: none;
  border: none;
  padding: 0;
  margin-left: 12px;
  color: #1e40af;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  transition: color 0.2s ease;
}


.btn-logout:hover {
  color: #ffffff;
}


.btn-logout.active {
  color: #ffffff;
  font-weight: 600;
}

</style>
