<template>
  <div class="app-container">
    <el-row type="flex" justify="center" align="middle">
      <el-card shadow="hover" style="width:70%;">
        <div slot="header" class="clearfix">
          <span>管理员信息</span>
        </div>
        <el-form :model="adminInfo" label-position="left" label-width="100px">
          <el-form-item label="权限类型">
            <el-select v-model="adminInfo.role_id" clearable placeholder="权限类型" style="width:100%;">
              <el-option
                v-for="item in roleTypeMap"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="管理员账号">
            <el-input v-model="adminInfo.username" placeholder="管理员账号"/>
          </el-form-item>
          <el-form-item label="管理员昵称">
            <el-input v-model="adminInfo.nickname" placeholder="管理员昵称"/>
          </el-form-item>
          <el-row type="flex" justify="center">
            <el-button type="primary" @click="addAdmin">添加</el-button>
            <el-button @click="cancel">取消</el-button>
          </el-row>
        </el-form>
      </el-card>
    </el-row>
  </div>
</template>

<script>
import { addAdmin } from '@/api/sys'
import { getRoleTypeList } from '@/api/role'

export default {
  name: 'AdminAdd',
  data() {
    return {
      adminInfo: {
        role_id: '',
        username: '',
        nickname: ''
      },
      roleTypeMap: []
    }
  },
  created() {},
  mounted() {
    getRoleTypeList().then(res => {
      this.roleTypeMap = res.data.data
    })
  },
  methods: {
    addAdmin() {
      addAdmin(this.adminInfo).then(res => {
        this.adminInfo = {
          role_id: '',
          username: '',
          nickname: ''
        }
        this.$message({
          message: '添加成功',
          type: 'success'
        })
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    cancel() {
      this.adminInfo = {
        role_id: '',
        username: '',
        nickname: ''
      }
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.el-card-first {
  margin-top: 0px;
}
</style>
