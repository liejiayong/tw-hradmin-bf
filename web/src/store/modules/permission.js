import { asyncRouterMap, constantRouterMap } from '@/router'

/**
 * 通过meta.permissionMap判断是否与当前用户权限匹配
 * @param router
 * @param permissionMap
 */
function hasPermission(router, permissionMap) {
  let hasPermission = false
  if (router.meta && router.meta.permission_uri) {
    const uri = router.meta.permission_uri
    let index
    for (index in permissionMap) {
      if (!permissionMap.hasOwnProperty(index)) continue
      if (permissionMap[index].uri === uri) {
        hasPermission = index
        break
      }
    }
  }
  return hasPermission
}

/**
 * 递归过滤异步路由表，返回符合用户角色权限的路由表
 * @param asyncRouterMap asyncRouterMap
 * @param resMap
 */
function filterAsyncRouter(asyncRouterMap, resMap) {
  return asyncRouterMap.filter(route => {
    if (route.hasOwnProperty('meta') && route.meta.permission_uri === 'all') {
      if (route.children && route.children.length) {
        route.children = filterAsyncRouter(route.children, resMap)
      }
      return true
    }
    const mapIndex = hasPermission(route, resMap)
    if (mapIndex !== false) {
      if (route.children && route.children.length) {
        route.children = filterAsyncRouter(route.children, resMap[mapIndex].children)
      }
      route.meta.title = resMap[mapIndex].title
      return true
    } else {
      return false
    }
  })
}

const permission = {
  state: {
    routers: constantRouterMap,
    addRouters: [],
    permission_uri: [],
    roleType: 0
  },
  mutations: {
    SET_ROUTERS: (state, routers) => {
      state.addRouters = routers
      state.routers = constantRouterMap.concat(routers)
    },
    SET_URI: (state, permission_uri) => {
      state.permission_uri = permission_uri
    },
    SET_ROLE_TYPE: (state, roleType) => {
      state.roleType = roleType
    }
  },
  actions: {
    GenerateRoutes({ commit }, data) {
      return new Promise(resolve => {
        const accessedRouters = filterAsyncRouter(asyncRouterMap, data.permission)
        commit('SET_ROUTERS', accessedRouters)
        commit('SET_URI', data.permission_uri)
        commit('SET_ROLE_TYPE', data.role_type)
        resolve()
      })
    }
  }
}

export default permission
