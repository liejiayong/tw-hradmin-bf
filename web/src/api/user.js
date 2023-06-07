import request from '@/utils/request'

export function getInfo(token) {
  return request({
    url: '',
    method: 'post',
    params: {
      c: 'Admin',
      m: 'info'
    },
    data: {
      token: token
    }
  })
}

export function changePassword(oldPwd, newPwd) {
  return request({
    url: '',
    method: 'post',
    params: {
      c: 'Admin',
      m: 'changeAdminPassword'
    },
    data: {
      old_password: oldPwd,
      new_password: newPwd,
      re_new_password: newPwd
    }
  })
}
