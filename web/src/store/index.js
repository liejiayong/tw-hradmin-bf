import Vue from 'vue'
import Vuex from 'vuex'
import app from './modules/app'
import dashboard from './modules/dashboard'
import user from './modules/user'
import permission from './modules/permission'
import getters from './getters'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    app,
    user,
    permission,
    dashboard
  },
  getters
})

export default store
