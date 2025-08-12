<template>
    <!--begin::User menu-->
    <div class="app-navbar-item ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
          <img src="/src/assets/media/avatars/300-1.jpg" alt="user" />
        </div>
        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
          <!--begin::Menu item-->
          <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
              <!--begin::Avatar-->
              <div class="symbol symbol-50px me-5">
                <img alt="Logo" :src="user.avatar" />
              </div>
              <!--end::Avatar-->
              <!--begin::Username-->
              <div class="d-flex flex-column">
                <div class="fw-bold d-flex align-items-center fs-5">{{ user.name }}
                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span></div>
                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ user.email }}</a>
              </div>
              <!--end::Username-->
            </div>
          </div>
          <!--end::Menu item-->

          <MenuSeparator></MenuSeparator>

          <MenuItem title="My Profile" link="Dashboard" :counter="0"></MenuItem>
          <MenuItem v-if="false" title="My Projects" link="Dashboard" :counter="3"></MenuItem>
          <MySubscription v-if="false"></MySubscription>
          <Languages v-if="true"></Languages>
          <MenuItem title="Account Settings" link="Dashboard" :counter="0"></MenuItem>

          <MenuSeparator></MenuSeparator>

          
          <!--begin::Menu item-->
          <div class="menu-item px-5">
            <a @click="logout" class="menu-link px-5">Sign Out</a>
          </div>
          <!--end::Menu item-->
        </div>
        <!--end::User account menu-->
        <!--end::Menu wrapper-->
      </div>
      <!--end::User menu-->
</template>

<script>
import MenuSeparator from '../../../../../../components/layouts/admin/header/partials/navbar/MenuSeparator.vue'
import MenuItem from '../../../../../../components/layouts/admin/header/partials/navbar/MenuItem.vue'
import MySubscription from '../../../../../../components/layouts/admin/header/partials/navbar/MySubscription.vue'
import Languages from '../../../../../../components/layouts/admin/header/partials/navbar/Languages.vue'

import {useStore} from 'vuex'
import {computed} from 'vue'
import {useRouter} from 'vue-router'
export default {
    name: "UserMenu",
    components: {
      MenuSeparator, MenuItem, MySubscription, Languages
    },
    setup(){
        const store = useStore();
        const router = useRouter();

        function logout(){
          store.dispatch("logout")
            .then(() => {
              router.push({
                name: 'Login'
              });
            }); 
        }

        return {
          user: computed(() => store.state.user.data),
          logout
        }
    }
}
</script>

<style scoped>

</style>