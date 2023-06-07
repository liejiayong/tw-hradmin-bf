<template>
  <el-scrollbar wrap-class="scrollbar-wrapper">
    <div class="m-logo">
      <span class="logo">招聘后台</span>
    </div>
    <el-menu
      :show-timeout="200"
      :default-active="$route.path"
      :collapse="isCollapse"
      mode="vertical"
      background-color="#304156"
      text-color="#bfcbd9"
      active-text-color="#409EFF"
    >
      <sidebar-item v-for="route in permission_routers" :key="route.path" :item="route" :base-path="route.path"/>
    </el-menu>
  </el-scrollbar>
</template>

<script>
import { mapGetters } from 'vuex'
import SidebarItem from './SidebarItem'
export default {
  components: { SidebarItem },
  computed: {
    ...mapGetters([
      'permission_routers',
      'sidebar'
    ]),
    isCollapse() {
      return !this.sidebar.opened
    }
  }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
.m-logo {
  position: relative;
  width: 180px;
  height: 49px;
  padding: 0;
  font-size: 12px;
  color: #fff;
  line-height: 30px;
  background-color: #304156;
  .logo {
    font-family: '微软雅黑';
    font-size: 18px;
    position: relative;
    top: 12px;
    left: 54px;
    display: inline-block;
    width: 88px;
    height: 25px;
    // background: url(//image.tanwan.com/platform/2018/img/logo.png) no-repeat;
    // background-position: left center;
    // background-size: contain;
  }
}
</style>
