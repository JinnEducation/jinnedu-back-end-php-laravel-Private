<template>
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <MenuSubItem 
            v-for="item in children" 
            :item="item">
        </MenuSubItem>
    </div>
    <!--end:Menu sub-->
</template>

<script>
import MenuSubItem from '../../../../../components/layouts/admin/sidebar/partials/MenuSubItem.vue'

export default {
    name: "MenuSub",
    props :{
        children: Object
    },
    components: {
        MenuSubItem,
    }
}
</script>

<style scoped>

</style>