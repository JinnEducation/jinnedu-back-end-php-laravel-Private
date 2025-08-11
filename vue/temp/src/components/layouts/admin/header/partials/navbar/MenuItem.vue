<template>
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <router-link :to="{name: link}"  class="menu-link px-5">
            <span class="menu-text">{{ title }}</span>
            <span v-if="counter > 0" class="menu-badge">
                <span class="badge badge-light-danger badge-circle fw-bold fs-7">{{ counter }}</span>
            </span>
            </router-link>
        </div>
        <!--end::Menu item-->
</template>

<script>
export default {
    name: "MenuItem",
    props: {
        title: String,
        link: String,
        counter: Number
    }
}
</script>

<style scoped>

</style>