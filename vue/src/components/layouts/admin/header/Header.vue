<template>
        <!--begin::Header-->
        <div id="kt_app_header" class="app-header">
            <!--begin::Header container-->
            <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
            <SidebarMobileToggle></SidebarMobileToggle>
            <Logo></Logo>
            <!--begin::Header wrapper-->
            <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                <MenuWrapper></MenuWrapper>
                <Navbar></Navbar>
            </div>
            <!--end::Header wrapper-->
            </div>
            <!--end::Header container-->
        </div>
        <!--end::Header-->
</template>

<script>
import SidebarMobileToggle from '../../../../components/layouts/admin/header/partials/SidebarMobileToggle.vue'
import Logo from '../../../../components/layouts/admin/header/partials/Logo.vue'
import MenuWrapper from '../../../../components/layouts/admin/header/partials/MenuWrapper.vue'
import Navbar from '../../../../components/layouts/admin/header/partials/Navbar.vue'
export default {
    name: "Header",
    components: {
        SidebarMobileToggle, 
        Logo,
        MenuWrapper,
        Navbar
    }
}
</script>

<style scoped>

</style>