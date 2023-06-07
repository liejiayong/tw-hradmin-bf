import request from '@/utils/request'

export function getAdminList(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'Admin',
      m: 'getAdminList'
    }
  }).then(res => {
    return res
  })
}

export function stopAdmin(id) {
  return request({
    url: '',
    method: 'post',
    data: {
      admin_id: id
    },
    params: {
      c: 'Admin',
      m: 'stopAdmin'
    }
  })
}

export function startAdmin(id) {
  return request({
    url: '',
    method: 'post',
    data: {
      admin_id: id
    },
    params: {
      c: 'Admin',
      m: 'startAdmin'
    }
  })
}

export function addAdmin(data) {
  return request({
    url: '',
    method: 'post',
    data,
    params: {
      c: 'Admin',
      m: 'addAdmin'
    }
  }).then(res => {
    return res
  })
}

