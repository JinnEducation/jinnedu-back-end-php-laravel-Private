import AuthAdminLayout from '../components/layouts/admin/AuthAdminLayout.vue'
import Login from '../views/admin/Login.vue'
import Register from '../views/admin/Register.vue'

export const auth_routers={
    path: '/auth',
    redirect: '/login',
    name: 'Auth',
    component: AuthAdminLayout,
    meta: {isGuest: true},
    children: [
        {path: '/login', name: 'Login', component: Login},
        {path: '/register', name: 'Register', component: Register}
    ]
};