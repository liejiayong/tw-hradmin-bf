(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-1ee7"],{MORE:function(t,n,e){"use strict";e.r(n);var a=e("Op4l"),u=e.n(a);for(var r in a)"default"!==r&&function(t){e.d(n,t,function(){return a[t]})}(r);n.default=u.a},Op4l:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var a=e("X9Rj");n.default={name:"Dashboard",data:function(){return{total:{orderSuccessNumber:0,withdrawSuccessNumber:0,pay:0,service:0,tranceNeed:0},ipaynow:{fromAccount:"/",incomeAccount:"/"}}},created:function(){},mounted:function(){this.sysData()},methods:{sysData:function(){var t=this;(0,a.sysData)().then(function(n){t.total=n.data.data.total,t.ipaynow=n.data.data.total}).catch(function(){})}}}},PkTg:function(t,n,e){"use strict";var a=e("wk/2");e.n(a).a},X9Rj:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.sysData=function(){return(0,a.default)({url:"",method:"post",params:{c:"Dashboard",m:"index"}})};var a=function(t){return t&&t.__esModule?t:{default:t}}(e("t3Un"))},lAbF:function(t,n,e){"use strict";e.r(n);var a=e("rfZh"),u=e("MORE");for(var r in u)"default"!==r&&function(t){e.d(n,t,function(){return u[t]})}(r);e("PkTg");var o=e("KHd+"),c=Object(o.a)(u.default,a.a,a.b,!1,null,"5655d5ee",null);c.options.__file="index.vue",n.default=c.exports},rfZh:function(t,n,e){"use strict";var a=function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"app-container"})},u=[];e.d(n,"a",function(){return a}),e.d(n,"b",function(){return u})},"wk/2":function(t,n,e){}}]);