<template>
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
      <!--begin::Authentication - Sign-in -->
      <div class="d-flex flex-column flex-lg-row flex-column-fluid">
          <!--begin::Body-->
          <router-view></router-view>
          <!--end::Body-->
          <!--begin::Aside-->
          <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url(/src/assets/media/misc/auth-bg.png)">
              <!--begin::Content-->
              <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                  <!--begin::Logo-->
                  <a href="/metronic8/demo1/../demo1/index.html" class="mb-0 mb-lg-12">
                      <img alt="Logo" src="/src/assets/media/logos/custom-1.png" class="h-60px h-lg-75px" />
                  </a>
                  <!--end::Logo-->
                  <!--begin::Image-->
                  <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="/src/assets/media/misc/auth-screens.png" alt="" />
                  <!--end::Image-->
                  <!--begin::Title-->
                  <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Fast, Efficient and Productive</h1>
                  <!--end::Title-->
                  <!--begin::Text-->
                  <div class="d-none d-lg-block text-white fs-base text-center">In this kind of post, 
                  <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a>introduces a person they’ve interviewed 
                  <br />and provides some background information about 
                  <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a>and their 
                  <br />work following this is a transcript of the interview.</div>
                  <!--end::Text-->
              </div>
              <!--end::Content-->
          </div>
          <!--end::Aside-->
      </div>
      <!--end::Authentication - Sign-in-->
  </div>
  <!--end::Root-->
  </template>
  
  <script>
  import {useStore} from 'vuex'
  import {computed} from 'vue'
  
  export default {
    name: "AuthAdminLayout",
    mounted() {
      console.log('lang storage: '+sessionStorage.getItem('LANGUAGE'));
      const ustore = useStore();
      var lang = sessionStorage.getItem('LANGUAGE') || 'ar';
      ustore.dispatch("setLang", lang);

    }
  }
  </script>
  
  <style scoped>
  
  </style>