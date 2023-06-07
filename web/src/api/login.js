import request from '@/utils/request'

export function login(username, password, captcha) {
  return request({
    url: '',
    method: 'post',
    data: {
      username: username,
      password: password,
      captcha: captcha
    },
    params: {
      c: 'Admin',
      m: 'login'
    }
  })
}

export function captcha() {
  return request({
    url: '/sp/captcha',
    method: 'post'
  })
}

export function logout() {
  return request({
    url: '',
    method: 'post',
    params: {
      c: 'Admin',
      m: 'logout'
    }
  })
}
