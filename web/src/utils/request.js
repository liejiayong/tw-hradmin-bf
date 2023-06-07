import axios from 'axios'
import { Message, MessageBox } from 'element-ui'
import store from '../store'
import { getToken } from '@/utils/auth'

// 创建axios实例
const service = axios.create({
  baseURL: process.env.BASE_API, // api 的 base_url
  timeout: 10000, // 请求超时时间
  withCredentials: true,
  crossDomain: true
})
// request拦截器
service.interceptors.request.use(
  config => {
    if (getToken()) {
      config.headers['X-Token'] = getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
    }
    return config
  },
  error => {
    // Do something with request error
    console.log(error) // for debug
    Promise.reject(error)
  }
)

// response 拦截器
service.interceptors.response.use(
  response => {
    const res = response.data
    if (res.errCode === 1002) {
      MessageBox.confirm(
        '你已被登出，请重新登录',
        '确定登出',
        {
          confirmButtonText: '重新登录',
          type: 'danger'
        }
      ).then(() => {
        store.dispatch('FedLogOut').then(() => {
          location.reload() // 为了重新实例化vue-router对象 避免bug
        })
      })
    } else {
      if (parseInt(res.code) === 1) {
        return response
      } else {
        return Promise.reject(res.msg)
      }
    }
  },
  error => {
    console.log(error) // for debug
    Message({
      message: '接口访问错误',
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
