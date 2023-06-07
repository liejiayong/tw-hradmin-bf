import request from '@/utils/request'

export function sysData() {
  return request({
    url: '',
    method: 'post',
    params: {
      c: 'Dashboard',
      m: 'index'
    }
  })
}
