<template>
  <div class="app-container">
    <el-row type="flex" justify="center" align="middle">
      <el-card shadow="hover" style="width:70%;">
        <div slot="header" class="clearfix">
          <span>角色信息</span>
        </div>
        <el-form :model="roleInfo" label-position="left" label-width="100px">
          <el-form-item label="角色名称">
            <el-input v-model="roleInfo.name" placeholder="角色名称"/>
          </el-form-item>
          <el-form-item label="角色描述">
            <el-input v-model="roleInfo.desc" placeholder="角色描述"/>
          </el-form-item>
          <el-row type="flex" justify="center">
            <el-button type="primary" @click="addRole">添加</el-button>
            <el-button @click="cancel">取消</el-button>
          </el-row>
        </el-form>
      </el-card>
    </el-row>
  </div>
</template>

<script>
import { addRole } from '@/api/role'

export default {
  name: 'RoleAdd',
  data() {
    return {
      roleInfo: {
        name: '',
        desc: ''
      }
    }
  },
  created() {},
  mounted() {},
  methods: {
    addRole() {
      addRole(this.roleInfo).then(res => {
        this.roleInfo = {
          name: '',
          desc: ''
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
      this.roleInfo = {
        name: '',
        desc: ''
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
