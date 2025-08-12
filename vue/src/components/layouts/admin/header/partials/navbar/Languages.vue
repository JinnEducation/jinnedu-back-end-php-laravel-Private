<template>
    <!--begin::Menu item-->
    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
        <a href="#" class="menu-link px-5">
          <span class="menu-title position-relative">Language 
          <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English 
          <img class="w-15px h-15px rounded-1 ms-2" src="/src/assets/media/flags/united-states.svg" alt="" /></span></span>
        </a>
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-dropdown w-175px py-4">
          <!--begin::Menu item-->
          <div class="menu-item px-3">
            <a @click="setLang('en')" class="menu-link d-flex px-5 active">
            <span class="symbol symbol-20px me-4">
              <img class="rounded-1" src="/src/assets/media/flags/united-states.svg" alt="" />
            </span>English</a>
          </div>
          <!--end::Menu item-->
          <!--begin::Menu item-->
          <div class="menu-item px-3">
            <a @click="setLang('ar')" class="menu-link d-flex px-5">
            <span class="symbol symbol-20px me-4">
              <img class="rounded-1" src="/src/assets/media/flags/saudi-arabia.svg" alt="" />
            </span>Arabic</a>
          </div>
          <!--end::Menu item-->
          
        </div>
        <!--end::Menu sub-->
      </div>
      <!--end::Menu item-->
</template>

<script>
import {useStore} from 'vuex'
import {computed} from 'vue'
import {useRouter} from 'vue-router'
import {loadLanguageAsync} from '@/i18n'

export default {
    name: "Languages",
    setup(){
        const store = useStore();
        const router = useRouter();

        function setLang(lang){
          //store.dispatch("setLang", lang); 
          loadLanguageAsync(lang).then(() => store.dispatch("setLang", lang));
        }

        return {
          lang: computed(() => store.state.lang.data),
          setLang
        }
    }
}
</script>

<style scoped>

</style>