(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-60d7"],{A4J9:function(e,t,n){"use strict";var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"app-container"},[n("el-row",{attrs:{type:"flex",justify:"center",align:"middle"}},[n("el-card",{staticStyle:{width:"70%"},attrs:{shadow:"hover"}},[n("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[n("span",[e._v("管理员信息")])]),e._v(" "),n("el-form",{attrs:{model:e.adminInfo,"label-position":"left","label-width":"100px"}},[n("el-form-item",{attrs:{label:"权限类型"}},[n("el-select",{staticStyle:{width:"100%"},attrs:{clearable:"",placeholder:"权限类型"},model:{value:e.adminInfo.role_id,callback:function(t){e.$set(e.adminInfo,"role_id",t)},expression:"adminInfo.role_id"}},e._l(e.roleTypeMap,function(e){return n("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})}))],1),e._v(" "),n("el-form-item",{attrs:{label:"管理员账号"}},[n("el-input",{attrs:{placeholder:"管理员账号"},model:{value:e.adminInfo.username,callback:function(t){e.$set(e.adminInfo,"username",t)},expression:"adminInfo.username"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"管理员昵称"}},[n("el-input",{attrs:{placeholder:"管理员昵称"},model:{value:e.adminInfo.nickname,callback:function(t){e.$set(e.adminInfo,"nickname",t)},expression:"adminInfo.nickname"}})],1),e._v(" "),n("el-row",{attrs:{type:"flex",justify:"center"}},[n("el-button",{attrs:{type:"primary"},on:{click:e.addAdmin}},[e._v("添加")]),e._v(" "),n("el-button",{on:{click:e.cancel}},[e._v("取消")])],1)],1)],1)],1)],1)},r=[];n.d(t,"a",function(){return a}),n.d(t,"b",function(){return r})},AsNN:function(e,t,n){"use strict";var a=n("YovS");n.n(a).a},BLof:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=n("Z5fj"),r=n("zF5t");t.default={name:"AdminAdd",data:function(){return{adminInfo:{role_id:"",username:"",nickname:""},roleTypeMap:[]}},created:function(){},mounted:function(){var e=this;(0,r.getRoleTypeList)().then(function(t){e.roleTypeMap=t.data.data})},methods:{addAdmin:function(){var e=this;(0,a.addAdmin)(this.adminInfo).then(function(t){e.adminInfo={role_id:"",username:"",nickname:""},e.$message({message:"添加成功",type:"success"})}).catch(function(t){e.$message({message:t,type:"error"})})},cancel:function(){this.adminInfo={role_id:"",username:"",nickname:""}}}}},IssH:function(e,t,n){"use strict";n.r(t);var a=n("BLof"),r=n.n(a);for(var i in a)"default"!==i&&function(e){n.d(t,e,function(){return a[e]})}(i);t.default=r.a},YovS:function(e,t,n){},Z5fj:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.getAdminList=function(e){return(0,a.default)({url:"",method:"post",data:e,params:{c:"Admin",m:"getAdminList"}}).then(function(e){return e})},t.stopAdmin=function(e){return(0,a.default)({url:"",method:"post",data:{admin_id:e},params:{c:"Admin",m:"stopAdmin"}})},t.startAdmin=function(e){return(0,a.default)({url:"",method:"post",data:{admin_id:e},params:{c:"Admin",m:"startAdmin"}})},t.addAdmin=function(e){return(0,a.default)({url:"",method:"post",data:e,params:{c:"Admin",m:"addAdmin"}}).then(function(e){return e})};var a=function(e){return e&&e.__esModule?e:{default:e}}(n("t3Un"))},"ljU/":function(e,t,n){"use strict";n.r(t);var a=n("A4J9"),r=n("IssH");for(var i in r)"default"!==i&&function(e){n.d(t,e,function(){return r[e]})}(i);n("AsNN");var o=n("KHd+"),d=Object(o.a)(r.default,a.a,a.b,!1,null,"b8717c4e",null);d.options.__file="adminAdd.vue",t.default=d.exports},zF5t:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.addRole=function(e){return(0,a.default)({url:"",method:"post",data:e,params:{c:"Admin",m:"addRole"}}).then(function(e){return e})},t.updateRole=function(e){return(0,a.default)({url:"",method:"post",data:{id:e.id,name:e.name,desc:e.desc},params:{c:"Admin",m:"updateRole"}}).then(function(e){return e})},t.getRoleList=function(e){return(0,a.default)({url:"",method:"post",data:e,params:{c:"Admin",m:"getRoleList"}}).then(function(e){return e})},t.getRoleTypeList=function(e){return(0,a.default)({url:"",method:"post",data:e,params:{c:"Admin",m:"getRoleTypeMap"}}).then(function(e){return e})},t.getRolePermission=function(e){return(0,a.default)({url:"",method:"post",data:{id:e},params:{c:"Admin",m:"getRolePermission"}}).then(function(e){return e})},t.modifyRolePermission=function(e,t){return(0,a.default)({url:"",method:"post",data:{role_id:e,permission:t},params:{c:"Admin",m:"updateRolePermission"}}).then(function(e){return e})},t.getRoleUser=function(e){return(0,a.default)({url:"",method:"post",data:{role_id:e},params:{c:"Admin",m:"getRoleUser"}}).then(function(e){return e})},t.modifyRoleUser=function(e,t){return(0,a.default)({url:"",method:"post",data:{role_id:e,admin_id_array:t},params:{c:"Admin",m:"updateUserRole"}}).then(function(e){return e})},t.getAllPermission=function(){return(0,a.default)({url:"",method:"post",params:{c:"Admin",m:"getAllPermission"}}).then(function(e){return e})};var a=function(e){return e&&e.__esModule?e:{default:e}}(n("t3Un"))}}]);