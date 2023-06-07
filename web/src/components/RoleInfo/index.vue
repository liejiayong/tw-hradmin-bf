<template>
  <el-row type="flex" justify="center" align="middle">
    <el-card shadow="hover" style="width:70%;">
      <div slot="header" class="clearfix">
        <span>角色信息</span>
      </div>
      <el-form :model="roleDetail" label-position="left" label-width="100px">
        <el-form-item label="ID">
          <el-input v-model="roleDetail.id" disabled/>
        </el-form-item>
        <el-form-item label="名称">
          <el-input v-model="roleDetail.name"/>
        </el-form-item>
        <el-form-item label="描述">
          <el-input v-model="roleDetail.desc"/>
        </el-form-item>
        <el-form-item label="创建时间">
          <el-input v-model="roleDetail.create_time" disabled/>
        </el-form-item>
        <el-row type="flex" justify="center">
          <el-button type="primary" @click="updateRole">更新</el-button>
          <el-button @click="backList">取消</el-button>
        </el-row>
      </el-form>
    </el-card>
  </el-row>
</template>

<script>
import { updateRole } from '@/api/role'

export default {
  name: 'RoleInfo',
  props: {
    roleDetail: {
      type: Object,
      required: true
    },
    roleDetailIndex: {
      type: Number,
      required: true
    }
  },
  data() {
    return {}
  },
  watch: {},
  created() {},
  mounted() {},
  methods: {
    backList() {
      this.$emit('backList')
    },
    updateRole() {
      var data = this.roleDetail
      data.role_id = this.roleDetail.id
      updateRole(data).then(res => {
        this.$message({
          message: '更新成功',
          type: 'success'
        })
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
</style>
