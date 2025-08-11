<template>
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
          <Header></Header>
          <!--begin::Wrapper-->
          <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <Sidebar></Sidebar>
            <!--begin::Main-->
            <router-view></router-view>
            <!--end:::Main-->
          </div>
          <!--end::Wrapper-->
        </div>
        <!--end::Page-->
      </div>
      <!--end::App-->

      <AppLayoutBuilder v-if="false"></AppLayoutBuilder>
      <AppSettingsToggle v-if="false"></AppSettingsToggle>

      <!--begin::Drawers-->
      <ActivitiesDrawer></ActivitiesDrawer>
      <ChatDrawer></ChatDrawer>
      <!--end::Drawers-->

      <!--begin::Engage drawers-->
      <DemosDrawer v-if="false"></DemosDrawer>
      <HelpDrawer v-if="false"></HelpDrawer>
      <!--end::Engage drawers-->

      <EngageToolbar v-if="false"></EngageToolbar>
      <ScrollTop></ScrollTop>
      
      <!--begin::Modals-->
      <InviteFriendsModal></InviteFriendsModal>
      <UpgradePlanModal></UpgradePlanModal>
      <UsersSearchModal></UsersSearchModal>
      <!--end::Modals-->
    </template>
    
    <script>
    import Header from '../../../components/layouts/admin/header/Header.vue'
    import Sidebar from '../../../components/layouts/admin/sidebar/Sidebar.vue'
    

    import AppLayoutBuilder from '../../../components/layouts/admin/partials/AppLayoutBuilder.vue'
    import AppSettingsToggle from '../../../components/layouts/admin/partials/AppSettingsToggle.vue'

    import InviteFriendsModal from '../../../components/layouts/admin/modals/InviteFriendsModal.vue'
    import UpgradePlanModal from '../../../components/layouts/admin/modals/UpgradePlanModal.vue'
    import UsersSearchModal from '../../../components/layouts/admin/modals/UsersSearchModal.vue'

    import ActivitiesDrawer from '../../../components/layouts/admin/partials/ActivitiesDrawer.vue'
    import ChatDrawer from '../../../components/layouts/admin/partials/ChatDrawer.vue'

    import DemosDrawer from '../../../components/layouts/admin/partials/DemosDrawer.vue'
    import HelpDrawer from '../../../components/layouts/admin/partials/HelpDrawer.vue'

    import EngageToolbar from '../../../components/layouts/admin/partials/EngageToolbar.vue'
    import ScrollTop from '../../../components/layouts/admin/partials/ScrollTop.vue'
    
    import {useStore} from 'vuex'
    import {computed} from 'vue'

    export default {
        name: "DefaultAdminLayout",
        components: {
          Header, Sidebar,
          AppLayoutBuilder, AppSettingsToggle,
          InviteFriendsModal, UpgradePlanModal, UsersSearchModal, 
          ActivitiesDrawer, ChatDrawer,
          DemosDrawer, HelpDrawer,
          EngageToolbar, ScrollTop
        },
        setup(){
            const store = useStore();

            return {
              user: computed(() => store.state.user.data),
            }
        },
        mounted() {
          
          const widgetsbundleScript = document.createElement("script");
          widgetsbundleScript.setAttribute( "src", "/src/assets/js/widgets.bundle.js" );
          document.body.appendChild(widgetsbundleScript);

          const widgetsScript = document.createElement("script");
          widgetsScript.setAttribute("src", "/src/assets/js/custom/widgets.js");
          document.body.appendChild(widgetsScript);

          const chatScript = document.createElement("script");
          chatScript.setAttribute("src","/src/assets/js/custom/apps/chat/chat.js" );
          document.body.appendChild(chatScript);

          const ustore = useStore();

          var lang = sessionStorage.getItem('LANGUAGE');

          if(!lang) {
              lang='ar';
              sessionStorage.setItem('LANGUAGE', lang);
          }

          ustore.dispatch("setLang", lang); 

        }
    }
    </script>

    <style scoped>
    
    </style>