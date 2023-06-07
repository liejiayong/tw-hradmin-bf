<template>
  <div class="app-container">
    <h1 class="app-title">密码设置</h1>
    <el-form ref="pwdForm" :model="pwdForm" :rules="pwdForm.rules" label-width="100px">
      <el-form-item label="原密码" prop="old">
        <el-input
          v-model="pwdForm.old"
          :type="pwdForm.type.old"
          style="width: 300px"
          placeholder="请输入原密码"
        />
        <span class="show-pwd" @click="showPwd('old')">
          <svg-icon icon-class="eye"/>
        </span>
      </el-form-item>
      <el-form-item label="新密码" prop="new">
        <el-input
          v-model="pwdForm.new"
          :type="pwdForm.type.new"
          style="width: 300px"
          placeholder="请输入新密码"
        />
        <span class="show-pwd" @click="showPwd('new')">
          <svg-icon icon-class="eye"/>
        </span>
      </el-form-item>
      <el-form-item label="确认新密码" prop="confirm">
        <el-input
          v-model="pwdForm.confirm"
          :type="pwdForm.type.confirm"
          style="width: 300px"
          placeholder="确认新密码"
        />
        <span class="show-pwd" @click="showPwd('confirm')">
          <svg-icon icon-class="eye"/>
        </span>
      </el-form-item>
      <el-form-item>
        <el-button :loading="pwdForm.loading" type="primary" @click="changePassword()">保存</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import md5 from 'js-md5'
import store from '@/store'
import { changePassword } from '@/api/user'
import { Message } from 'element-ui'

export default {
  name: 'UserInfo',
  data() {
    var confirmPass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'))
      } else if (value !== this.pwdForm.new) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      headImgList: [
        {
          url: store.getters.avatar
        }
      ],
      userInfoForm: {
        account: store.getters.account,
        name: store.getters.name,
        avatar: store.getters.avatar,
        loading: false,
        rules: {
          name: [
            { required: true, message: '请输入昵称', trigger: ['blur'] }
          ]
        }
      },
      pwdForm: {
        old: '',
        new: '',
        confirm: '',
        loading: false,
        type: {
          old: 'password',
          new: 'password',
          confirm: 'password'
        },
        rules: {
          old: [
            { required: true, message: '请输入旧密码', trigger: ['blur'] }
          ],
          new: [
            { required: true, message: '请输入新密码', trigger: ['blur'] }
          ],
          confirm: [
            { required: true, validator: confirmPass, trigger: ['blur'] }
          ]
        }
      }
    }
  },
  methods: {
    showPwd(index) {
      if (this.pwdForm.type[index] === 'password') {
        this.pwdForm.type[index] = ''
      } else {
        this.pwdForm.type[index] = 'password'
      }
    },
    changePassword() {
      this.$refs.pwdForm.validate((valid) => {
        if (valid) {
          this.pwdForm.loading = true
          changePassword(this.pwdForm.old, this.pwdForm.new).then(response => {
            this.pwdForm.loading = false
            Message({
              message: '修改成功',
              type: 'success',
              duration: 5 * 1000
            })
            this.pwdForm.old = ''
            this.pwdForm.new = ''
            this.pwdForm.confirm = ''
          }).catch(() => {
            this.pwdForm.loading = false
          })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  margin-top: 10px;
  margin-left: 100px;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 120px;
  height: 120px;
  display: block;
  padding: 20px;
}
</style>
<style rel="stylesheet/scss" lang="scss" scoped>
.show-pwd {
  position: absolute;
  left: 270px;
  color: #8c939d;
  cursor: pointer;
}
</style>

