<template>
  <div class="app-container">
    <el-row v-if="isList" style="display:flex;flex-direction:column;height:100%">
      <el-row>
        <el-row style="margin-top:15px;padding-bottom:60px;flex:1;">
          <el-table key="userList" :data="roleList" max-height="698" style="width:100%;height:698px;overflow:auto">
            <el-table-column fixed prop="id" label="角色ID" width/>
            <el-table-column prop="name" label="角色名称" width/>
            <el-table-column prop="desc" label="角色描述" width/>
            <el-table-column prop="state" label="状态" width>
              <template slot-scope="scope">
                <el-link v-if="scope.row.state == 1" :underline="false" type="success">正常</el-link>
                <el-link v-if="scope.row.state == -1" :underline="false" type="danger">停用</el-link>
              </template>
            </el-table-column>
            <el-table-column prop="create_time" label="创建时间" width/>
            <el-table-column prop="option" label="操作" width>
              <template slot-scope="scope">
                <el-tooltip class="item" effect="dark" content="查看资料" placement="bottom">
                  <el-button
                    type="text"
                    icon="el-icon-tickets"
                    @click="showRoleDetail(scope.row,scope.$index)"
                  />
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="设置权限" placement="bottom">
                  <el-button
                    type="text"
                    icon="el-icon-s-tools"
                    @click="showTreeDialog(scope.row.id,scope.$index)"
                  />
                </el-tooltip>
                <el-tooltip class="item" effect="dark" content="用户授权" placement="bottom">
                  <el-button
                    type="text"
                    icon="el-icon-s-custom"
                    @click="showTransferDialog(scope.row.id,scope.$index)"
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
    <el-row v-else>
      <role-info
        :role-detail="roleDetail"
        :role-detail-index="roleDetailIndex"
        @backList="backList"
      />
    </el-row>
    <el-dialog :visible.sync="dialogTreeVisible" :title="dialogTreeTitle">
      <el-tree
        ref="tree"
        :props="defaultProps"
        :data="treeData"
        :expand-on-click-node="false"
        :check-strictly="true"
        show-checkbox
        default-expand-all
        node-key="id"
        highlight-current
        check-on-click-node
      />
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogTreeVisible = false">取 消</el-button>
        <el-button type="primary" @click="modifyRolePermission">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogTransferVisible" :title="dialogTransferTitle">
      <el-row type="flex" justify="center" align="middle">
        <el-transfer
          :titles="['待选用户', '已选用户']"
          :filter-method="filterMethod"
          v-model="transferSelectedValue"
          :data="transferData"
          filterable
          filter-placeholder="请输入关键字"
        />
      </el-row>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogTransferVisible = false">取 消</el-button>
        <el-button type="primary" @click="modifyRoleUser">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import RoleInfo from '@/components/RoleInfo'
import {
  getRoleList,
  getRolePermission,
  modifyRolePermission,
  getRoleUser,
  modifyRoleUser,
  getAllPermission
} from '@/api/role'
export default {
  name: 'AdminRole',
  components: {
    RoleInfo
  },
  data() {
    return {
      isList: true,
      currentRoleId: 0,
      formLabelWidth: '120px',
      dialogTreeTitle: '设置权限',
      dialogTreeVisible: false,
      dialogTransferTitle: '用户授权',
      dialogTransferVisible: false,
      search: {
        page: 1,
        limit: 10,
        total: 0
      },
      roleList: [],
      treeData: [],
      defaultProps: {
        children: 'children',
        label: 'title'
      },
      transferData: [],
      transferSelectedValue: [],
      filterMethod(query, item) {
        // return item.label.indexOf(query) > -1
        return true
      }
    }
  },
  created() {},
  mounted() {
    this.getRoleList()
    this.getAllPermission()
  },
  methods: {
    getAllPermission() {
      getAllPermission().then(res => {
        this.treeData = res.data.data
      })
    },
    getRoleList() {
      getRoleList(this.search).then(res => {
        this.roleList = res.data.data.data
        this.search.total = res.data.data.total
      })
    },
    showRoleDetail(data, index) {
      this.roleDetail = data
      this.roleDetailIndex = index
      this.isList = false
    },
    changePageSize(pageSize) {
      this.search.limit = pageSize
      getRoleList(this.search).then(res => {
        this.roleList = res.data.data.data
        this.search.total = res.data.data.total
      })
    },
    changePage(page) {
      this.search.page = page
      getRoleList(this.search).then(res => {
        this.roleList = res.data.data.data
        this.search.total = res.data.data.total
      })
    },
    backList() {
      this.isList = true
    },
    showTreeDialog(id, index) {
      this.currentRoleId = id
      this.dialogTreeTitle = `设置权限(${this.roleList[index].name})`
      getRolePermission(id).then(res => {
        this.dialogTreeVisible = true
        this.$nextTick(() => {
          this.$refs.tree.setCheckedKeys(
            res.data.data.permission_list.split(',')
          )
        })
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    showTransferDialog(id, index) {
      this.currentRoleId = id
      this.dialogTransferTitle = `用户授权(${this.roleList[index].name})`
      getRoleUser(id).then(res => {
        this.transferData = res.data.data.all
        this.transferSelectedValue = res.data.data.selected
        this.dialogTransferVisible = true
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    modifyRolePermission() {
      const roleId = this.currentRoleId
      const permission = this.$refs.tree.getCheckedKeys().join(',')
      modifyRolePermission(roleId, permission).then(res => {
        this.$message({
          message: '设置权限成功',
          type: 'success'
        })
        this.dialogTreeVisible = false
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    modifyRoleUser() {
      const roleId = this.currentRoleId
      const adminIdArr = this.transferSelectedValue
      modifyRoleUser(roleId, adminIdArr).then(res => {
        this.$message({
          message: '用户授权成功',
          type: 'success'
        })
        this.dialogTransferVisible = false
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
