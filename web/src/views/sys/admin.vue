<template>
  <div class="app-container">
    <el-row style="display:flex;flex-direction:column;height:100%">
      <el-row>
        <el-row type="flex" justify="space-between" style="width:100%;">
          <el-row type="flex">
            <el-input
              v-model="search.username"
              style="width:150px;margin-left:3px;"
              placeholder="管理员账号"
            />
            <el-input
              v-model="search.nickname"
              style="width:150px;margin-left:3px;"
              placeholder="管理员昵称"
            />
            <el-button type="primary" style="margin-left:10px;" @click="search.page=1;getAdminList()">查询</el-button>
          </el-row>
        </el-row>
        <el-row style="margin-top:15px;padding-bottom:60px;flex:1;">
          <el-table key="userList" :data="adminList" max-height="698" style="width:100%;height:698px;overflow:auto">
            <el-table-column fixed prop="id" label="用户ID" width=""/>
            <el-table-column prop="role_id_ch" label="管理员角色" width=""/>
            <el-table-column prop="username" label="管理员账号" width=""/>
            <el-table-column prop="nickname" label="管理员昵称" width=""/>
            <el-table-column prop="last_login_time" label="最后登录时间" width=""/>
            <el-table-column prop="create_time" label="创建时间" width=""/>
            <el-table-column prop="option" label="操作" width="">
              <template slot-scope="scope">
                <el-tooltip v-if="scope.row.status != -1" class="item" effect="dark" content="封号" placement="bottom" >
                  <el-button
                    type="text"
                    icon="el-icon-error"
                    @click="stopAdmin(scope.row.id,scope.$index)"
                  />
                </el-tooltip>
                <el-tooltip v-else class="item" effect="dark" content="启用" placement="bottom">
                  <el-button
                    type="text"
                    icon="el-icon-success"
                    @click="startAdmin(scope.row.id,scope.$index)"
                  />
                </el-tooltip>
              </template>
            </el-table-column>
          </el-table>
        </el-row>
        <el-row class="page-block-body" type="flex">
          <el-pagination
            :current-page="search.page"
            :page-sizes="[10,15,20,50,100, 200, 300, 400]"
            :page-size="search.pageSize"
            :total="search.total"
            class="page-block"
            background
            layout="total, sizes, prev, pager, next, jumper"
            @size-change="changePageSize"
            @current-change="changePage"
          />
        </el-row>
      </el-row>
    </el-row>
  </div>
</template>
<script>
import { getAdminList, stopAdmin, startAdmin } from '@/api/sys'
export default {
  name: 'Admin',
  data() {
    return {
      search: {
        uid: '',
        username: '',
        nickname: '',
        page: 1,
        limit: 10,
        total: 0
      },
      adminList: []
    }
  },
  created() { },
  mounted() {
    this.getAdminList()
  },
  methods: {
    getAdminList() {
      getAdminList(this.search).then(res => {
        this.adminList = res.data.data.data
        this.search.total = res.data.data.total
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    changePageSize(pageSize) {
      this.search.limit = pageSize
      getAdminList(this.search).then(res => {
        this.adminList = res.data.data.data
        this.search.total = res.data.data.total
      })
    },
    changePage(page) {
      this.search.page = page
      getAdminList(this.search).then(res => {
        this.adminList = res.data.data.data
        this.search.total = res.data.data.total
      })
    },
    stopAdmin(id, index) {
      stopAdmin(id).then(res => {
        this.adminList[index].status = -1
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    startAdmin(id, index) {
      startAdmin(id).then(res => {
        this.adminList[index].status = 1
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
</style>
