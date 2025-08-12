<template>
        <!--begin:Menu item-->
        <div data-kt-menu-trigger="click" 
            :class="[
                'menu-item',
                item.type==='parent'?'menu-accordion':item.type==='content'?'pt-5':'',
                item.current?'here':'',
                (item.current && item.type==='parent')?'show':''
            ]">
            <MenuLink v-if="item.type != 'content'" :item="item" :type="item"></MenuLink>
            <MenuSub v-if="item.type === 'parent'" :children="item.children"></MenuSub>
            <MenuContent v-if="item.type === 'content'"></MenuContent>
        </div>
        <!--end:Menu item-->
</template>

<script>
import MenuLink from '@/components/layouts/admin/sidebar/partials/MenuLink.vue'
import MenuSub from '@/components/layouts/admin/sidebar/partials/MenuSub.vue'
import MenuContent from '@/components/layouts/admin/sidebar/partials/MenuContent.vue'
export default {
    name: "MenuItem",
    props :{
        item: Object
    },
    components: {
        MenuSub,MenuLink,MenuContent
    }
}
</script>

<style scoped>

</style>