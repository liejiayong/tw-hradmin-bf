const getters = {
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  id: state => state.user.id,
  name: state => state.user.name,
  nickname: state => state.user.nickname,
  account: state => state.user.account,
  permission_routers: state => state.permission.routers,
  permissionUri: state => state.permission.permission_uri,
  roleType: state => state.permission.roleType,
  addRouters: state => state.permission.addRouters
}
export default getters
