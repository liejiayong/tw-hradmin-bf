import request from '@/utils/request'

export function addRole(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'Admin',
      m: 'addRole'
    }
  }).then(res => {
    return res
  })
}

export function updateRole(data) {
  return request({
    url: '',
    method: 'post',
    data: {
      id: data.id,
      name: data.name,
      desc: data.desc
    },
    params: {
      c: 'Admin',
      m: 'updateRole'
    }
  }).then(res => {
    return res
  })
}

export function getRoleList(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'Admin',
      m: 'getRoleList'
    }
  }).then(res => {
    return res
  })
}

export function getRoleTypeList(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'Admin',
      m: 'getRoleTypeMap'
    }
  }).then(res => {
    return res
  })
}

export function getRolePermission(id) {
  return request({
    url: '',
    method: 'post',
    data: {
      id: id
    },
    params: {
      c: 'Admin',
      m: 'getRolePermission'
    }
  }).then(res => {
    return res
  })
}

export function modifyRolePermission(id, permission) {
  return request({
    url: '',
    method: 'post',
    data: {
      role_id: id,
      permission
    },
    params: {
      c: 'Admin',
      m: 'updateRolePermission'
    }
  }).then(res => {
    return res
  })
}

export function getRoleUser(id) {
  return request({
    url: '',
    method: 'post',
    data: {
      role_id: id
    },
    params: {
      c: 'Admin',
      m: 'getRoleUser'
    }
  }).then(res => {
    return res
  })
}

export function modifyRoleUser(id, adminIdArr) {
  return request({
    url: '',
    method: 'post',
    data: {
      role_id: id,
      admin_id_array: adminIdArr
    },
    params: {
      c: 'Admin',
      m: 'updateUserRole'
    }
  }).then(res => {
    return res
  })
}

export function getAllPermission() {
  return request({
    url: '',
    method: 'post',
    params: {
      c: 'Admin',
      m: 'getAllPermission'
    }
  }).then(res => {
    return res
  })
}
