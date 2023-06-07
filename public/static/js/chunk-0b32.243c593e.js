(window.webpackJsonp=window.webpackJsonp||[]).push([["chunk-0b32"],{"5JLb":function(t,e,a){},CzTM:function(t,e,a){"use strict";a.r(e);var n=a("cjSf"),i=a("k4SI");for(var s in i)"default"!==s&&function(t){a.d(e,t,function(){return i[t]})}(s);a("LXXg");var r=a("KHd+"),c=Object(r.a)(i.default,n.a,n.b,!1,null,"0950b076",null);c.options.__file="admin.vue",e.default=c.exports},LXXg:function(t,e,a){"use strict";var n=a("5JLb");a.n(n).a},Rhcx:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=a("Z5fj");e.default={name:"Admin",data:function(){return{search:{uid:"",username:"",nickname:"",page:1,limit:10,total:0},adminList:[]}},created:function(){},mounted:function(){this.getAdminList()},methods:{getAdminList:function(){var t=this;(0,n.getAdminList)(this.search).then(function(e){t.adminList=e.data.data.data,t.search.total=e.data.data.total}).catch(function(e){t.$message({message:e,type:"error"})})},changePageSize:function(t){var e=this;this.search.limit=t,(0,n.getAdminList)(this.search).then(function(t){e.adminList=t.data.data.data,e.search.total=t.data.data.total})},changePage:function(t){var e=this;this.search.page=t,(0,n.getAdminList)(this.search).then(function(t){e.adminList=t.data.data.data,e.search.total=t.data.data.total})},stopAdmin:function(t,e){var a=this;(0,n.stopAdmin)(t).then(function(t){a.adminList[e].status=-1}).catch(function(t){a.$message({message:t,type:"error"})})},startAdmin:function(t,e){var a=this;(0,n.startAdmin)(t).then(function(t){a.adminList[e].status=1}).catch(function(t){a.$message({message:t,type:"error"})})}}}},Z5fj:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.getAdminList=function(t){return(0,n.default)({url:"",method:"post",data:t,params:{c:"Admin",m:"getAdminList"}}).then(function(t){return t})},e.stopAdmin=function(t){return(0,n.default)({url:"",method:"post",data:{admin_id:t},params:{c:"Admin",m:"stopAdmin"}})},e.startAdmin=function(t){return(0,n.default)({url:"",method:"post",data:{admin_id:t},params:{c:"Admin",m:"startAdmin"}})},e.addAdmin=function(t){return(0,n.default)({url:"",method:"post",data:t,params:{c:"Admin",m:"addAdmin"}}).then(function(t){return t})};var n=function(t){return t&&t.__esModule?t:{default:t}}(a("t3Un"))},cjSf:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("el-row",{staticStyle:{display:"flex","flex-direction":"column",height:"100%"}},[a("el-row",[a("el-row",{staticStyle:{width:"100%"},attrs:{type:"flex",justify:"space-between"}},[a("el-row",{attrs:{type:"flex"}},[a("el-input",{staticStyle:{width:"150px","margin-left":"3px"},attrs:{placeholder:"管理员账号"},model:{value:t.search.username,callback:function(e){t.$set(t.search,"username",e)},expression:"search.username"}}),t._v(" "),a("el-input",{staticStyle:{width:"150px","margin-left":"3px"},attrs:{placeholder:"管理员昵称"},model:{value:t.search.nickname,callback:function(e){t.$set(t.search,"nickname",e)},expression:"search.nickname"}}),t._v(" "),a("el-button",{staticStyle:{"margin-left":"10px"},attrs:{type:"primary"},on:{click:function(e){t.search.page=1,t.getAdminList()}}},[t._v("查询")])],1)],1),t._v(" "),a("el-row",{staticStyle:{"margin-top":"15px","padding-bottom":"60px",flex:"1"}},[a("el-table",{key:"userList",staticStyle:{width:"100%",height:"698px",overflow:"auto"},attrs:{data:t.adminList,"max-height":"698"}},[a("el-table-column",{attrs:{fixed:"",prop:"id",label:"用户ID",width:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"role_id_ch",label:"管理员角色",width:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"username",label:"管理员账号",width:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"nickname",label:"管理员昵称",width:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"last_login_time",label:"最后登录时间",width:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"create_time",label:"创建时间",width:""}}),t._v(" "),a("el-table-column",{attrs:{prop:"option",label:"操作",width:""},scopedSlots:t._u([{key:"default",fn:function(e){return[-1!=e.row.status?a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"封号",placement:"bottom"}},[a("el-button",{attrs:{type:"text",icon:"el-icon-error"},on:{click:function(a){t.stopAdmin(e.row.id,e.$index)}}})],1):a("el-tooltip",{staticClass:"item",attrs:{effect:"dark",content:"启用",placement:"bottom"}},[a("el-button",{attrs:{type:"text",icon:"el-icon-success"},on:{click:function(a){t.startAdmin(e.row.id,e.$index)}}})],1)]}}])})],1)],1),t._v(" "),a("el-row",{staticClass:"page-block-body",attrs:{type:"flex"}},[a("el-pagination",{staticClass:"page-block",attrs:{"current-page":t.search.page,"page-sizes":[10,15,20,50,100,200,300,400],"page-size":t.search.pageSize,total:t.search.total,background:"",layout:"total, sizes, prev, pager, next, jumper"},on:{"size-change":t.changePageSize,"current-change":t.changePage}})],1)],1)],1)],1)},i=[];a.d(e,"a",function(){return n}),a.d(e,"b",function(){return i})},k4SI:function(t,e,a){"use strict";a.r(e);var n=a("Rhcx"),i=a.n(n);for(var s in n)"default"!==s&&function(t){a.d(e,t,function(){return n[t]})}(s);e.default=i.a}}]);