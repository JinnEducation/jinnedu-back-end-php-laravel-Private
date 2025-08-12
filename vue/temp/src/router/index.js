import {createRouter, createWebHistory} from "vue-router";


import store from '../store'

import { dashboard_routers } from '../router/dashboard.js'
import { auth_routers } from '../router/auth.js'

const routes = [
    dashboard_routers,
    auth_routers
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if(to.meta.requiresAuth && !store.state.user.token){
        next({name: 'Login'})
    } else if(store.state.user.token && (to.meta.isGuest)){
        next({name: 'Dashboard'})
    } else {
        next()
    }
})

export default router;