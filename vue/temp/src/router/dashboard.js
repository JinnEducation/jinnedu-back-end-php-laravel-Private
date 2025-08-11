import DefaultAdminLayout from '../components/layouts/admin/DefaultAdminLayout.vue'
import Dashboard from '../views/admin/Dashboard.vue'
import PosSystem from '../views/admin/PosSystem.vue'

export const dashboard_routers={
    path: '/',
    redirect: '/dashboard',
    component: DefaultAdminLayout,
    meta: {requiresAuth: true},
    children: [
        {path: '/dashboard', name: 'Dashboard', component: Dashboard},
        {path: '/pos-system', name: 'PosSystem', component: PosSystem}
    ]
};