<template>
    <!--begin::Menu-->
    <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
        <MenuItem 
            v-for="item in navigation" 
            :item="item">
        </MenuItem>
    </div>
    <!--end::Menu-->
</template>

<script>
import MenuItem from '../../../../../components/layouts/admin/sidebar/partials/MenuItem.vue'
import {useStore} from 'vuex'
import {computed} from 'vue'
export default {
    name: "Menu",
    setup(){
        const store = useStore();

        return {
            navigation: computed(() => store.state.user.navigation),
        }
    },
    components: {
        MenuItem,
    }
}
</script>

<style scoped>

</style>