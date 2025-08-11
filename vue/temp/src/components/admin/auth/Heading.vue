<template>
    <!--begin::Heading-->
    <div class="text-center mb-11">
        <!--begin::Title-->
        <h1 class="text-dark fw-bolder mb-3">{{title}}</h1>
        <!--end::Title-->
        <!--begin::Subtitle-->
        <div class="text-gray-500 fw-semibold fs-6">{{subtitle}}</div>
        <!--end::Subtitle=-->
    </div>
    <!--begin::Heading-->
</template>

<script>
export default {
    name: "Heading",
    props: {
        title: String,
        subtitle: String
    }
}
</script>

<style scoped>

</style>