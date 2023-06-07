import Vue from 'vue'
import Router from 'vue-router'

// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
import Layout from '../views/layout/Layout'

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
  }
**/
export const constantRouterMap = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    meta: {
      permission_uri: '/dashboard'
    },
    children: [
      {
        path: 'dashboard',
        name: '数据主页',
        component: () => import('@/views/dashboard/index'),
        meta: {
          title: '数据主页',
          icon: 'home',
          permission_uri: '/dashboard/list'
        }
      }
    ]
  },
  {
    path: '/manager',
    component: Layout,
    hidden: true,
    redirect: '/manager/info',
    children: [
      {
        path: 'info',
        name: '个人信息',
        component: () => import('@/views/manager/info'),
        meta: {
          icon: 'home',
          title: '个人信息'
        }
      }
    ]
  }
]

export const asyncRouterMap = [
  {
    path: '/jobseekermaterial',
    redirect: '/jobseekermaterial/index',
    component: Layout,
    name: '资料管理',
    meta: {
      title: '资料管理',
      icon: 'prize',
      permission_uri: '/jobseekermaterial'
    },
    children: [
      {
        path: 'index',
        name: '入职人员信息登记表',
        component: () => import('@/views/jobseekermaterial/index'),
        meta: {
          icon: 'index',
          title: '入职人员信息登记表',
          permission_uri: '/jobseekermaterial/index'
        }
      }
    ]
  },
  {
    path: '/sys',
    component: Layout,
    name: '系统管理',
    meta: {
      title: '系统管理',
      icon: 'setting',
      permission_uri: '/sys'
    },
    redirect: '/sys/admin',
    children: [
      {
        path: 'index',
        name: '系统用户',
        component: () => import('@/views/sys/admin'),
        meta: {
          icon: 'admin',
          title: '系统用户',
          permission_uri: '/sys/admin'
        }
      },
      {
        path: 'add',
        name: '添加系统用户',
        component: () => import('@/views/sys/adminAdd'),
        meta: {
          icon: 'add',
          title: '添加系统用户',
          permission_uri: '/sys/admin/add'
        }
      },
      {
        path: 'role',
        name: '系统角色',
        component: () => import('@/views/sys/role'),
        meta: {
          icon: 'sysRole',
          title: '系统角色',
          permission_uri: '/sys/role'
        }
      },
      {
        path: '/role/add',
        name: '添加系统角色',
        component: () => import('@/views/sys/roleAdd'),
        meta: {
          icon: 'sysRoleAdd',
          title: '系统角色',
          permission_uri: '/sys/role/add'
        }
      }
    ]
  },
  {
    path: '*',
    redirect: '/404',
    hidden: true,
    meta: {
      permission_uri: 'all'
    }
  }
]

export default new Router({
  // mode: 'history', // 后端支持可开
  scrollBehavior: () => ({
    y: 0
  }),
  routes: constantRouterMap
})
