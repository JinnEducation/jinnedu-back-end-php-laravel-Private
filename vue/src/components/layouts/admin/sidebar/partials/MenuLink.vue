<template>
    <!--begin:Menu link-->
    <span v-if="item.type === 'parent'" class="menu-link">
        <span class="menu-icon">
            <MenuSvg :item="item"></MenuSvg>
        </span>
        <span class="menu-title">{{ $t(item.title) }}</span>
        <span class="menu-arrow"></span>
    </span>
    <router-link v-else-if="item.type === 'child'" 
        active-class="active"
        :to="{name: item.link}" 
        :class="[
            'menu-link',
            this.$route.name === item.link?'':''
            ]">
            <span class="menu-bullet">
                <span class="bullet bullet-dot"></span>
            </span>
        <span class="menu-title">{{ $t(item.title) }}</span>
    </router-link>
    <!--end:Menu link-->
</template>

<script>
import MenuSvg from '../../../../../components/layouts/admin/sidebar/partials/MenuSvg.vue'
export default {
    name: "MenuLink",
    components: {
        MenuSvg,
    },
    props :{
        item: Object
    }
}
</script>

<style scoped>

</style>