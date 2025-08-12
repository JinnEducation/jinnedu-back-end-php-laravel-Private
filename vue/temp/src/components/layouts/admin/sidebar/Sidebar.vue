<template>
  <!--begin::Sidebar-->
  <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <LogoToggle></LogoToggle>
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <Menu></Menu>
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
    <Footer></Footer>
  </div>
  <!--end::Sidebar-->
</template>
  
<script>
import LogoToggle from '../../../../components/layouts/admin/sidebar/partials/LogoToggle.vue'
import Menu from '../../../../components/layouts/admin/sidebar/partials/Menu.vue'
import Footer from '../../../../components/layouts/admin/sidebar/partials/Footer.vue'
export default {
    name: "Sidebar",
    components: {
        LogoToggle,
        Menu,
        Footer
    }
}
</script>

<style scoped>

</style>