<template>
  <PageComponent title="Dashboard">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
      <!--begin::Row-->
      <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
          <CardWidget20></CardWidget20>
          <CardWidget07></CardWidget07>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
          <CardWidget17></CardWidget17>
          <CardWidget26></CardWidget26>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xxl-6">
          <EngageWidget10></EngageWidget10>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <div class="row gx-5 gx-xl-10">
        <!--begin::Col-->
        <div class="col-xxl-6 mb-5 mb-xl-10">
          <ChartWidget08></ChartWidget08>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-6 mb-5 mb-xl-10">
          <TableWidget16></TableWidget16>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-xxl-6">
          <CardWidget18></CardWidget18>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-6">
          <ChartWidget36></ChartWidget36>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-xl-4">
          <ChartWidget35></ChartWidget35>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-8">
          <TableWidget14></TableWidget14>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <div class="row gx-5 gx-xl-10">
        <!--begin::Col-->
        <div class="col-xl-4">
          <ChartWidget31></ChartWidget31>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-8">
          <ChartWidget24></ChartWidget24>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Content container-->
  </PageComponent>
</template>

<script>
import PageComponent from '../../components/admin/PageComponent.vue'


import CardWidget07 from '../../components/admin/widgets/CardWidget07.vue'
import CardWidget17 from '../../components/admin/widgets/CardWidget17.vue'
import CardWidget18 from '../../components/admin/widgets/CardWidget18.vue'
import CardWidget20 from '../../components/admin/widgets/CardWidget20.vue'
import CardWidget26 from '../../components/admin/widgets/CardWidget26.vue'

import EngageWidget10 from '../../components/admin/widgets/EngageWidget10.vue'

import ChartWidget08 from '../../components/admin/widgets/ChartWidget08.vue'
import ChartWidget24 from '../../components/admin/widgets/ChartWidget24.vue'
import ChartWidget31 from '../../components/admin/widgets/ChartWidget31.vue'
import ChartWidget35 from '../../components/admin/widgets/ChartWidget35.vue'
import ChartWidget36 from '../../components/admin/widgets/ChartWidget36.vue'

import TableWidget14 from '../../components/admin/widgets/TableWidget14.vue'
import TableWidget16 from '../../components/admin/widgets/TableWidget16.vue'



export default {
    name: "Dashboard",
    components: {
      PageComponent,
      CardWidget07, CardWidget17, CardWidget18, CardWidget20, CardWidget26,
      EngageWidget10,
      ChartWidget08, ChartWidget24, ChartWidget31, ChartWidget35, ChartWidget36,
      TableWidget14, TableWidget16,
    },
    methods: {
        dynamicallyLoadScript(url) {
            var script = document.createElement("script");
            script.src = url;
            console.log(script.src);
            //var scriptElement = document.body.appendChild(script);
            this.scriptEle.push(document.body.appendChild(script));
        }
    },
    beforeCreate() {
      console.log('At this point, events and lifecycle have been initialized.('+this.scriptEle.length+")");
      this.scriptEle.forEach(element => {
        console.log('delete script');
        document.body.removeChild(element);
      });
      this.scriptEle=[];
    },
    created() {
      //alert('done');
    },
    beforeDestroy() {
      console.log(`At this point, watchers, child components, and event listeners have not been teared down yet.`)
      // Perform the teardown procedure for exampleLeakyProperty.
      // (In this case, effectively nothing)
      //if(this.scriptEle) document.body.removeChild(this.scriptEle);
    },
    mounted() {
      //alert('123');
      KTUtil.onDOMContentLoaded(function () {
        KTComponents.init();
        KTApp.initPageLoader();
        //KTAppLayoutBuilder.init();
        //KTLayoutSearch.init();
        KTThemeModeUser.init();
        KTThemeMode.init();
        KTAppSidebar.init();
        KTLayoutToolbar.init();
        //alert('258');
      });
      /*=====amcharts start======
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/index.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/xy.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/percent.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/radar.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/themes/Animated.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/map.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/geodata/worldLow.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/geodata/continentsLow.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/geodata/usaLow.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js");
      this.dynamicallyLoadScript("https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js");
      =====amcharts end======*/
      //this.dynamicallyLoadScript("/src/assets/js/custom/utilities/modals/upgrade-plan.js");
      //this.dynamicallyLoadScript("src/assets/js/custom/utilities/modals/users-search.js");
      //this.dynamicallyLoadScript("/src/assets/js/custom/utilities/modals/create-app.js");
      
      //this.dynamicallyLoadScript("/src/assets/js/custom/utilities/modals/new-target.js");
      //this.dynamicallyLoadScript("/src/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js");
      //this.dynamicallyLoadScript("/src/assets/plugins/custom/datatables/datatables.bundle.js");
    }
}
</script>

<style scoped>

</style>