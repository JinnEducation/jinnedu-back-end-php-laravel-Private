<template>
    <!--begin::Navbar-->
    <div class="app-navbar flex-shrink-0">
        <Search v-if="false"></Search>
        <Activities v-if="false"></Activities>
        <Notifications></Notifications>
        <Chat v-if="false"></Chat>
        <MyAppsLinks v-if="false"></MyAppsLinks>
        <ThemeMode></ThemeMode>
        <UserMenu></UserMenu>
        <HeaderMenuToggle></HeaderMenuToggle>
      </div>
      <!--end::Navbar-->
</template>

<script>
import Search from '../../../../../components/layouts/admin/header/partials/navbar/Search.vue'
import Activities from '../../../../../components/layouts/admin/header/partials/navbar/Activities.vue'
import Notifications from '../../../../../components/layouts/admin/header/partials/navbar/Notifications.vue'
import Chat from '../../../../../components/layouts/admin/header/partials/navbar/Chat.vue'
import MyAppsLinks from '../../../../../components/layouts/admin/header/partials/navbar/MyAppsLinks.vue'
import ThemeMode from '../../../../../components/layouts/admin/header/partials/navbar/ThemeMode.vue'
import UserMenu from '../../../../../components/layouts/admin/header/partials/navbar/UserMenu.vue'
import HeaderMenuToggle from '../../../../../components/layouts/admin/header/partials/navbar/HeaderMenuToggle.vue'

export default {
    name: "Navbar",
    components: {
      Search, Activities, Notifications, Chat, MyAppsLinks, ThemeMode, UserMenu, HeaderMenuToggle
    }
}
</script>

<style scoped>

</style>