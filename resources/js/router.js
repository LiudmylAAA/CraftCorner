import { createRouter, createWebHistory } from 'vue-router';

import MainPage from './components/MainPage.vue';
import UserDashboard from './components/UserDashboard.vue';
import CatalogPage from './components/Catalog.vue';
import AboutPage from './components/About.vue';
import PostDetails from './components/PostDetails.vue';

const routes = [
    { path: '/', name: 'home', component: MainPage },
    { path: '/catalog', name: 'catalog', component: CatalogPage },

    // ✅ сторінка одного поста
    { path: '/posts/:id', name: 'post-details', component: PostDetails },

    { path: '/about', name: 'about', component: AboutPage },

    { 
        path: '/dashboard', 
        name: 'dashboard', 
        component: UserDashboard, 
        meta: { requiresAuth: true } 
    },

    { path: '/:pathMatch(.*)*', redirect: '/' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (to.meta.requiresAuth && !user) next('/');
    else next();
});

export default router;
