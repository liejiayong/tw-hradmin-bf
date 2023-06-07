<template>
  <div v-show="canAddRole">
    <el-form ref="role" :model="role" :rules="role.rules" label-width="80px">
      <div class="role-setting">
        <div class="role-setting-title">
          <span>基本设置</span>
        </div>
        <div class="role-setting-content">
          <div class="role-setting-type">
            <el-form-item label="角色类型" prop="type">
              <el-radio-group v-model="role.type">
                <el-radio :label="2">管理者</el-radio>
                <el-radio :label="3">普通客服</el-radio>
                <!-- <el-radio :label="4">工单客服</el-radio> -->
              </el-radio-group>
            </el-form-item>
          </div>
          <div class="role-setting-name">
            <el-form-item label="角色名称" prop="name">
              <el-input v-model="role.name" style="width: 300px" placeholder="请输入角色名称"/>
            </el-form-item>
          </div>
          <div class="role-setting-desc">
            <el-form-item label="角色描述" prop="desc">
              <el-input
                :rows="4"
                v-model="role.desc"
                style="width: 300px"
                type="textarea"
                placeholder="请输入角色描述"/>
            </el-form-item>
          </div>
        </div>
      </div>
      <div class="role-module">
        <div class="role-module-title">
          <small>启用后，创建的角色才能看到该模块</small>
        </div>
        <el-row v-for="(father, index) in permissionList" :key="index" style="margin-top:20px;">
          <el-card :body-style="{ padding: '0px', border: '1px #dbdbdb solid' }" shadow="always">
            <div class="role-module-item">
              <div class="role-module-item-title">
                <span>{{ father.title }}</span>
              </div>
              <div class="role-module-item-content">
                <el-form-item label="权限" prop="permissionList">
                  <el-checkbox
                    v-for="(children, index) in father.children"
                    :key="index"
                    :label="children"
                    v-model="role.permissionList">
                    {{ children.title }}
                  </el-checkbox>
                </el-form-item>
              </div>
            </div>
          </el-card>
        </el-row>
      </div>
      <div class="role-fixed">
        <el-button :disabled="role.disabled" type="primary" style="position: fixed;left: 300px;bottom: 14px;" @click="handleAdd">保存</el-button>
        <el-button style="position: fixed;left: 400px;bottom: 14px;" @click="handleCancelAdd(false)">取消</el-button>
      </div>
    </el-form>
  </div>
</template>
<script>
import { Message } from 'element-ui'
import { add, getPermissionList } from '@/api/role'

export default {
  name: 'Setting',
  props: {
    addRole: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      canAddRole: this.addRole,
      permissionList: [],
      role: {
        type: 2,
        name: '',
        desc: '',
        permissionList: [],
        disabled: false,
        rules: {
          type: [
            { required: true, message: '请选择类型', trigger: ['blur'] }
          ],
          name: [
            { required: true, message: '请输入角色名称', trigger: ['blur'] }
          ],
          desc: [
            { required: true, message: '请输入角色描述', trigger: ['blur'] }
          ],
          permissionList: [
            { required: true, message: '请选择角色权限', trigger: ['blur'] }
          ]
        }
      }
    }
  },
  watch: {
    addRole: {
      handler(newValue, oldValue) {
        this.canAddRole = newValue
      },
      deep: true
    }
  },
  mounted() {
    getPermissionList().then(response => {
      this.permissionList = response.data.data.list
    }).catch(() => {
    })
  },
  methods: {
    handleCancelAdd(refresh) {
      this.canAddRole = false
      this.$emit('cancelAdd', false, refresh)
    },
    handleAdd() {
      this.$refs.role.validate((valid) => {
        if (valid) {
          const permissionList = this.getPermissionId()
          this.role.disabled = true
          add(this.role, permissionList).then(response => {
            this.role.disabled = false
            Message({
              message: response.data.message,
              type: 'success',
              duration: 5 * 1000
            })
            this.handleCancelAdd(true)
          }).catch(() => {
            this.role.disabled = false
          })
        } else {
          return false
        }
      })
    },
    getPermissionId() {
      const permissionList = []
      this.role.permissionList.forEach((item) => {
        permissionList.push(item.id, item.pid)
      })
      return Array.from(new Set(permissionList))
    }
  }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
.role-setting {
  &-title {
    font-size: 18px;
    color: #222;
    line-height: 1.5;
    border-bottom: 1px solid #e6e6e6;
    margin-bottom: 20px;
    padding-top: 14px;
    padding-bottom: 10px;
  }
  &-content {
    padding: 0 20px;
    &-desc {
      vertical-align: middle;
    }
    .after-star::after {
      content: '*';
			color: #ff1818;
    }
    &>div {
      margin: 20px 0;
    }
  }
}
.role-module {
  &-title {
    font-size: 18px;
    color: #222;
    line-height: 1.5;
    border-bottom: 1px solid #e6e6e6;
    margin-bottom: 20px;
    padding-top: 14px;
    padding-bottom: 10px;
    small {
      color: #999;
    }
  }
  &-item {
    &-title {
      padding: 12px 20px;
      font-size: 18px;
      line-height: 1.5;
      color: #222;
      background-color: #eee;
      small {
        margin-left: 10px;
        color: #999;
      }
    }
    &-content {
      padding: 20px {
        bottom: 0px;
      }
    }
  }
}
.role-fixed {
  height:63px;
  width:100%;
  background-color: #fff;
  border-top: 1px solid #ececec;
  position:fixed;
  z-index: 1;
  bottom:0;
  left:0;
}
</style>
