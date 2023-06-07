import { login, logout } from '@/api/login'
import { getInfo, changeInfo } from '@/api/user'
import { getToken, setToken, removeToken } from '@/utils/auth'

const user = {
  state: {
    token: getToken(),
    id: 0,
    name: '',
    avatar: '',
    nickname: '',
    account: '',
    user_id: '',
    status: 1
  },

  mutations: {
    SET_TOKEN: (state, token) => {
      state.token = token
    },
    SET_ID: (state, id) => {
      state.id = id
    },
    SET_NAME: (state, name) => {
      state.name = name
    },
    SET_AVATAR: (state, avatar) => {
      state.avatar = avatar
    },
    SET_NICKNAME: (state, nickname) => {
      state.nickname = nickname
    },
    SET_USER_ID: (state, user_id) => {
      state.user_id = user_id
    },
    SET_ACCOUNT: (state, account) => {
      state.account = account
    },
    SET_STATUS: (state, status) => {
      state.status = status
    }
  },
  actions: {// 登录
    Login({ commit }, userInfo) {
      const account = userInfo.account.trim()
      return new Promise((resolve, reject) => {
        login(account, userInfo.password, userInfo.captcha).then(response => {
          const data = response.data
          setToken(data.data.token)
          commit('SET_TOKEN', data.data.token)
          resolve()
        }).catch(error => {
          reject(error)
        })
      })
    },
    // 获取用户信息
    GetInfo({ commit, state }) {
      return new Promise((resolve, reject) => {
        getInfo(state.token).then(response => {
          const responseData = response.data
          const userInfo = responseData.data.user_info
          commit('SET_NAME', userInfo.nickname)
          commit('SET_NICKNAME', userInfo.nickname)
          commit('SET_ACCOUNT', userInfo.nickname)
          commit('SET_AVATAR', userInfo.avatar)
          commit('SET_ID', userInfo.id)
          commit('SET_USER_ID', userInfo.user_id)
          commit('SET_STATUS', userInfo.status)
          resolve(responseData.data)
        }).catch(error => {
          reject(error)
        })
      })
    },
    // 获取用户信息
    ChangeInfo({ commit, state }, userInfo) {
      return new Promise((resolve, reject) => {
        changeInfo(state.user_id, userInfo.name, userInfo.avatar).then(() => {
          commit('SET_NAME', userInfo.name)
          commit('SET_AVATAR', userInfo.avatar)
          commit('SET_NICKNAME', userInfo.avatar)
          resolve()
        }).catch(error => {
          reject(error)
        })
      })
    },
    // 登出
    LogOut({ commit, state }) {
      return new Promise((resolve, reject) => {
        logout(state.token).then(() => {
          commit('SET_TOKEN', '')
          removeToken()
          resolve()
        }).catch(error => {
          reject(error)
        })
      })
    },
    // 前端 登出
    FedLogOut({ commit }) {
      return new Promise(resolve => {
        commit('SET_TOKEN', '')
        removeToken()
        resolve()
      })
    }
  }
}

export default user
