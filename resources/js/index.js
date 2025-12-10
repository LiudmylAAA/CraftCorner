import { createRouter, createWebHistory } from 'vue-router';
import MainPage from '../components/MainPage.vue';
import UserDashboard from '../components/UserDashboard.vue';
import CatalogPage from '../components/Catalog.vue';
import AboutPage from '../components/About.vue';

const routes = [
    { path: '/', name: 'home', component: MainPage },
    { path: '/catalog', name: 'catalog', component: CatalogPage },
    { path: '/about', name: 'about', component: AboutPage },
    { path: '/:pathMatch(.*)*', redirect: '/' },
    { path: '/dashboard', name: 'dashboard', component: UserDashboard, meta: { requiresAuth: true } }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (to.meta.requiresAuth && !user) next('/');
    else next();
});

export default router;
