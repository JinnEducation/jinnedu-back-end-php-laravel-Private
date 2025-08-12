<template>
    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
    <span class="svg-icon svg-icon-2">
        <component :is="item.icon"></component>
    </span>
    <!--end::Svg Icon-->
</template>

<script>

export default {
    name: "MenuSvg",
    props: {
        item: Object
    }
}

</script>

<style scoped>

</style>