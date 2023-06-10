import {createRouter, createWebHistory} from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: () => import('./pages/Login.vue')
        },
        {
            path: '/register',
            component: () => import('./pages/Register.vue')
        },
        {
            path: '/home',
            component: () => import('./pages/Home.vue')
        },
        {
            path: '/account',
            component: () => import('./pages/Account.vue')
        },
        {
            path: '/favorites',
            component: () => import('./pages/Favorites.vue')
        },
        {
            path: '/account',
            component: () => import('./pages/Account.vue')
        },
        {
            path: '/business/:id',
            name: 'businessDetails',
            component: () => import('./pages/BusinessDetails.vue'),
            props: true
        }
    ],
})
router.beforeEach((to, from, next) => {
    if (to.path !== '/' && to.path !== '/register' && !isAuthenticated()) {
        return next({path: '/'})
    }
    return next()
})

function isAuthenticated() {
    return Boolean(localStorage.getItem('APP_USER_TOKEN'))
}

export default router;
