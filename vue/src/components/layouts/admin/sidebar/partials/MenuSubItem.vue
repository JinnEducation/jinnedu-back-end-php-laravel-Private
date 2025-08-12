<template>
        <!--begin:Menu Sub item-->
        <div class="menu-item">
            <MenuLink v-if="item.type != 'content'" :item="item"></MenuLink>
        </div>
        <!--end:Menu Sub item-->
</template>

<script>
import MenuLink from '../../../../../components/layouts/admin/sidebar/partials/MenuLink.vue'
export default {
    name: "MenuSubItem",
    props :{
        item: Object
    },
    components: {
        MenuLink,
    }
}
</script>

<style scoped>

</style>