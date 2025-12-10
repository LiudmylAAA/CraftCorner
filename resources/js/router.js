import { createRouter, createWebHistory } from 'vue-router';
import MainPage from './components/MainPage.vue';
import UserDashboard from './components/UserDashboard.vue';

const routes = [
  { path: '/', name: 'home', component: MainPage },
  { path: '/dashboard', name: 'dashboard', component: UserDashboard },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
