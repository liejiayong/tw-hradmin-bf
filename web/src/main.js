import Vue from 'vue'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/zh-CN' // lang i18n

import '@/styles/index.scss' // global css

import App from './App'
import router from './router'
import store from './store'

import '@/icons' // icon
import '@/permission' // permission control

import Viewer from 'v-viewer'
import 'viewerjs/dist/viewer.css'
Vue.use(ElementUI, { locale })

Vue.use(Viewer, {
  defaultOptions: {
    zIndex: 9999,
    inline: true,
    button: false, // 右上角按钮
    navbar: false, // 底部缩略图
    title: false, // 当前图片标题
    toolbar: true, // 底部工具栏
    tooltip: true, // 显示缩放百分比
    movable: true, // 是否可以移动
    zoomable: true, // 是否可以缩放
    rotatable: true, // 是否可旋转
    scalable: false, // 是否可翻转
    transition: true, // 使用 CSS3 过度
    fullscreen: false, // 播放时是否全屏
    keyboard: false, // 是否支持键盘
    url: 'src'
  }
})

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
