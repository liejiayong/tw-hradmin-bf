<template>
  <div class="app-container">
    <el-row style="display:flex;flex-direction:column;height:100%">
      <el-row>
        <el-row type="flex" justify="space-between" style="width:100%;">
          <el-row type="flex">
            <el-input
              v-model="search.name"
              style="width: 150px"
              placeholder="入职者姓名"
              clearable
            />
            <el-select v-model="search.state" placeholder="填写状态" style="margin-left: 3px;" clearable>
              <el-option label="" value="">全部</el-option>
              <el-option label="已填写" value="1"></el-option>
              <el-option label="未填写" value="0"></el-option>
            </el-select>
            <el-date-picker
              v-model="search.hire_date"
              value-format="yyyy-MM-dd"
              type="daterange"
              style="margin-left: 3px;"
              align="right"
              start-placeholder="入职日期"
              end-placeholder="入职日期">
            </el-date-picker>
            <el-button type="primary" style="margin-left:10px;" @click="search.page=1;getList()">查询</el-button>
          </el-row>
        </el-row>
        <el-row style="margin-top:10px;">
          <el-button type="success" size="small" @click="dialogAddFormVisible = true">添加</el-button>
          <el-button type="primary" size="small" @click="exportData()">导出</el-button>
          <el-button type="primary" size="small" @click="downloadFiles()">批量下载</el-button>
        </el-row>
        <el-row style="margin-top:15px;padding-bottom:60px;flex:1;">
          <el-table key="list" :data="materialList" max-height="698" style="width:100%;height:698px;overflow:auto" @selection-change="handleSelectionChange">>
            <el-table-column type="selection"></el-table-column>
            <el-table-column fixed prop="id" label="用户ID" width=""/>
            <el-table-column prop="name" label="入职者姓名" width=""/>
            <el-table-column prop="hire_date" label="入职时间" width=""/>
            <el-table-column prop="data" label="填写状态" width="">
              <template slot-scope="scope">
                <el-tag v-if="!scope.row.data" type="danger" disable-transitions>未填写</el-tag>
                <el-tag v-else type="success" disable-transitions>已填写</el-tag>
              </template>
            </el-table-column>
            <el-table-column prop="create_time" label="创建时间" width=""/>
            <el-table-column prop="update_time" label="更新时间" width=""/>
            <el-table-column prop="option" label="操作" width="400">
              <template slot-scope="scope">
                <el-button :data-clipboard-text="scope.row.url" type="text" class="copy-btn" >复制链接</el-button>
                <el-button type="text" class="copy-btn" @click="downloadQrcode(scope.row.name, scope.row.url)">下载二维码</el-button>
                <el-button type="text" class="copy-btn" @click="showUpdateFormDialog(scope.row)">编辑</el-button>
                <el-button type="text" @click="preview(scope.row.id)">预览</el-button>
                <el-button type="text" style="color: red" @click="deleteJobSeekerMaterial(scope.row.id)">删除</el-button>
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
    <canvas id="baseCanvas" style="display: none"></canvas>
    <el-dialog :visible.sync="dialogAddFormVisible" title="新增">
      <el-form ref="addForm" :model="addForm" :rules="addFormRules">
        <el-form-item :label-width="formLabelWidth" label="入职者姓名" prop="name">
          <el-input v-model="addForm.name" autocomplete="off" style="width: 200px;"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancelAddJobSeekerMaterial()">取 消</el-button>
        <el-button type="primary" @click="addJobSeekerMaterial()">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog :visible.sync="dialogUpdateFormVisible" :fullscreen="true" title="编辑" width="75%" @close="cancelUpdateJobSeekerMaterial">
      <el-tabs value="material-content">
        <el-tab-pane label="登记表编辑" name="material-content">
          <el-form
            ref="updateMaterialForm"
            :inline="true"
            :model="updateMaterialForm"
            :rules="updateMaterialFormRules"
            label-width="110px"
          >
            <el-row>
              <el-col :span="6">
                <el-form-item label="入职部门" prop="apply_department">
                  <el-input v-model="updateMaterialForm.apply_department"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="入职岗位" prop="apply_job">
                  <el-input v-model="updateMaterialForm.apply_job"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="入职前状态" prop="current_job_status">
                  <el-select v-model="updateMaterialForm.current_job_status">
                    <el-option label="已离职（解除劳动合同）" value="已离职"></el-option>
                    <el-option label="仍在职" value="仍在职"></el-option>
                    <el-option label="应届毕业生" value="应届毕业生"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="入职日期" prop="join_date">
                  <el-date-picker v-model="updateMaterialForm.join_date" value-format="yyyy-MM-dd" title="date" placeholder="选择日期"></el-date-picker>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="目前薪资" prop="current_job_pay">
                  <el-input v-model="updateMaterialForm.current_job_pay"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="6">
                <el-form-item label="姓名" prop="personal_truename">
                  <el-input v-model="updateMaterialForm.personal_truename"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="姓名全拼" prop="personal_truename_py">
                  <el-input v-model="updateMaterialForm.personal_truename_py"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="性别" prop="personal_sex">
                  <el-select v-model="updateMaterialForm.personal_sex">
                    <el-option label="男" value="男"></el-option>
                    <el-option label="女" value="女"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="出生日期" prop="personal_birthday">
                  <el-date-picker v-model="updateMaterialForm.personal_birthday" value-format="yyyy-MM-dd" title="date" placeholder="选择日期" ></el-date-picker>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="民族" prop="personal_nation">
                  <el-input v-model="updateMaterialForm.personal_nation"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="籍贯" prop="personal_nation_place">
                  <el-input v-model="updateMaterialForm.personal_nation_place"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="政治面貌" prop="personal_politics_role">
                  <el-select v-model="updateMaterialForm.personal_politics_role">
                    <el-option label="群众" value="群众"></el-option>
                    <el-option label="团员" value="团员"></el-option>
                    <el-option label="预备党员" value="预备党员"></el-option>
                    <el-option label="正式党员" value="正式党员"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="学历" prop="personal_education">
                  <el-select v-model="updateMaterialForm.personal_education">
                    <el-option label="小学" value="小学">小学</el-option>
                    <el-option label="初中" value="初中">初中</el-option>
                    <el-option label="中专" value="中专">中专</el-option>
                    <el-option label="高中" value="高中">高中</el-option>
                    <el-option label="高技" value="高技">高技</el-option>
                    <el-option label="大专" value="大专">大专</el-option>
                    <el-option label="本科" value="本科">本科</el-option>
                    <el-option label="硕士" value="硕士">硕士</el-option>
                    <el-option label="博士" value="博士">博士</el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="健康状况" prop="personal_health">
                  <el-input v-model="updateMaterialForm.personal_health"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="婚姻状况" prop="personal_marry_status">
                  <el-select v-model="updateMaterialForm.personal_marry_status">
                    <el-option label="已婚" value="已婚"></el-option>
                    <el-option label="未婚" value="未婚"></el-option>
                    <el-option label="离异" value="离异"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="电子邮箱" prop="personal_email">
                  <el-input v-model="updateMaterialForm.personal_email"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="微信号" prop="personal_wechat">
                  <el-input v-model="updateMaterialForm.personal_wechat"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="身份证地址" prop="personal_idcard_address">
                  <el-input v-model="updateMaterialForm.personal_idcard_address"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="身份证号" prop="personal_idcard">
                  <el-input v-model="updateMaterialForm.personal_idcard"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="现有效地址" prop="personal_address">
                  <el-input v-model="updateMaterialForm.personal_address"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="移动电话" prop="personal_phone">
                  <el-input v-model="updateMaterialForm.personal_phone"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="6">
                <el-form-item label="紧急联系人" prop="emergency_contact_name">
                  <el-input v-model="updateMaterialForm.emergency_contact_name"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="与本人关系" prop="emergency_contact_relation">
                  <el-select v-model="updateMaterialForm.emergency_contact_relation">
                    <el-option label="父母" value="父母"></el-option>
                    <el-option label="配偶" value="配偶"></el-option>
                    <el-option label="子女" value="子女"></el-option>
                    <el-option label="兄弟姐妹" value="兄弟姐妹"></el-option>
                    <el-option label="朋友" value="朋友"></el-option>
                  </el-select>
                  <!-- <el-input v-model="updateMaterialForm.emergency_contact_relation"></el-input> -->
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="紧急联系电话" prop="emergency_contact_phone">
                  <el-input v-model="updateMaterialForm.emergency_contact_phone"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="6">
                <el-form-item label="户口类别" prop="social_household_type">
                  <el-select v-model="updateMaterialForm.social_household_type">
                    <el-option label="本地城镇" value="本地城镇"></el-option>
                    <el-option label="本地农村" value="本地农村"></el-option>
                    <el-option label="外地城镇" value="外地城镇"></el-option>
                    <el-option label="外地农村" value="外地农村"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="是否缴过社保">
                  <el-select v-model="updateMaterialForm.social_insurance_is_pay">
                    <el-option label="否" value="0"></el-option>
                    <el-option label="是" value="1"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="投保城市">
                  <el-input v-model="updateMaterialForm.social_insurance_city"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="投保时间">
                  <el-col :span="10">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.social_insurance_start_date" placeholder="开始(年-月)"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="1">-</el-col>
                  <el-col :span="10">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.social_insurance_end_date" placeholder="结束（年-月）"></el-input>
                    </el-form-item>
                  </el-col>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="6">
                <el-form-item label="是否缴过公积金">
                  <el-select v-model="updateMaterialForm.housing_fund_is_pay">
                    <el-option label="否" value="0"></el-option>
                    <el-option label="是" value="1"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="投保城市">
                  <el-input v-model="updateMaterialForm.housing_fund_city"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="投保单位">
                  <el-input v-model="updateMaterialForm.housing_fund_company"></el-input>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="6">
                <el-form-item label="入司渠道">
                  <el-select v-model="updateMaterialForm.join_channel">
                    <el-option label="平面媒介" value="平面媒介"></el-option>
                    <el-option label="互联网" value="互联网"></el-option>
                    <el-option label="招聘会" value="招聘会"></el-option>
                    <el-option label="猎头推荐" value="猎头推荐"></el-option>
                    <el-option label="其他" value="其他"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="入司渠道名称">
                  <el-input v-model="updateMaterialForm.join_channel_name"></el-input>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="公司员工推荐">
                  <el-col :span="12">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.inner_recommend_name" placeholder="员工姓名"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="12">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.inner_recommend_relation" placeholder="与该员工关系"></el-input>
                    </el-form-item>
                  </el-col>
                </el-form-item>
              </el-col>
              <el-col :span="24">
                <el-form-item label="公司亲友">
                  <el-col :span="8">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.family_member_name" placeholder="姓名"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.family_member_job" placeholder="职务"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="8">
                    <el-form-item>
                      <el-input v-model="updateMaterialForm.family_member_relation" placeholder="关系"></el-input>
                    </el-form-item>
                  </el-col>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <h4>工作经历 <el-button type="success" icon="el-icon-plus" circle @click="addHistoryJobItem()"></el-button></h4>
              <el-divider></el-divider>
              <div v-for="(item, index) in updateMaterialForm.history_job" :key="index">
                <el-row>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_job.' + index + '.time'">
                      <el-input v-model="item.time" placeholder="起止时间"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_job.' + index + '.company'">
                      <el-input v-model="item.company" placeholder="公司名称"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_job.' + index + '.job'">
                      <el-input v-model="item.job" placeholder="职务"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_job.' + index + '.leave_reason'">
                      <el-input v-model="item.leave_reason" placeholder="离职原因"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_job.' + index + '.salary'">
                      <el-input v-model="item.salary" placeholder="薪资"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_job.' + index + '.witness'">
                      <el-input v-model="item.witness" placeholder="证明人/联系方式（座机）"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-button type="danger" icon="el-icon-minus" circle @click="deleteHistoryJobItem()"></el-button>
                  </el-col>
                </el-row>
              </div>
            </el-row>
            <el-row>
              <h4>教育经历 <el-button type="success" icon="el-icon-plus" circle @click="addHistoryEducationItem()"></el-button></h4>
              <el-divider></el-divider>
              <div v-for="(item, index) in updateMaterialForm.history_education" :key="index">
                <el-row>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_education.' + index + '.time'">
                      <el-input v-model="item.time" placeholder="起止时间"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_education.' + index + '.school'">
                      <el-input v-model="item.school" placeholder="学校名称"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_education.' + index + '.specialty'">
                      <el-input v-model="item.specialty" placeholder="专业"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_education.' + index + '.education'">
                      <el-input v-model="item.education" placeholder="学历"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_education.' + index + '.degree'">
                      <el-input v-model="item.degree" placeholder="学位"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-button type="danger" icon="el-icon-minus" circle @click="deleteHistoryEducationItem()"></el-button>
                  </el-col>
                </el-row>
              </div>
            </el-row>
            <el-row>
              <h4>培训情况 <el-button type="success" icon="el-icon-plus" circle @click="addHistoryTrainItem()"></el-button></h4>
              <el-divider></el-divider>
              <div v-for="(item, index) in updateMaterialForm.history_train" :key="index">
                <el-row>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_train.' + index + '.time'">
                      <el-input v-model="item.time" placeholder="起止时间"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_train.' + index + '.trainer'">
                      <el-input v-model="item.trainer" placeholder="培训/主办单位"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_train.' + index + '.content'">
                      <el-input v-model="item.content" placeholder="培训内容"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_train.' + index + '.certificate'">
                      <el-input v-model="item.certificate" placeholder="所获证书"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'history_train.' + index + '.certificate_number'">
                      <el-input v-model="item.certificate_number" placeholder="证书编号"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-button type="danger" icon="el-icon-minus" circle @click="deleteHistoryTrainItem()"></el-button>
                  </el-col>
                </el-row>
              </div>
            </el-row>
            <el-row>
              <h4>家庭资料 <el-button type="success" icon="el-icon-plus" circle @click="addFamilyMembersItem()"></el-button></h4>
              <el-divider></el-divider>
              <div v-for="(item, index) in updateMaterialForm.family_members" :key="index">
                <el-row>
                  <el-col :span="3">
                    <el-form-item
                      :prop="'family_members.' + index + '.name'"
                      :rules="{ required: true, message: '不能为空', trigger: 'blur' }"
                      label=""
                    >
                      <el-input v-model="item.name" placeholder="姓名"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item
                      :prop="'family_members.' + index + '.phone'"
                      :rules="{ required: true, message: '不能为空', trigger: 'blur' }"
                      label=""
                    >
                      <el-input v-model="item.phone" placeholder="联系电话"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item
                      :prop="'family_members.' + index + '.relation'"
                      :rules="{ required: true, message: '不能为空', trigger: 'blur' }"
                      label=""
                    >
                      <el-input v-model="item.relation" placeholder="与本人关系"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'family_members.' + index + '.unit'">
                      <el-input v-model="item.unit" placeholder="工作单位"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-form-item label="" :prop="'family_members.' + index + '.title'">
                      <el-input v-model="item.title" placeholder="职务"></el-input>
                    </el-form-item>
                  </el-col>
                  <el-col :span="3">
                    <el-button type="danger" icon="el-icon-minus" circle @click="deleteFamilyMembersItem()"></el-button>
                  </el-col>
                </el-row>
              </div>
            </el-row>
          </el-form>
          <div style="text-align: right;" class="dialog-footer">
            <el-button @click="cancelUpdateJobSeekerMaterial()">取 消</el-button>
            <el-button type="primary" @click="updateJobSeekerMaterialData()">确 定</el-button>
          </div>
        </el-tab-pane>
      </el-tabs>

    </el-dialog>
  </div>
</template>
<script>
import Clipboard from 'clipboard'
import QRCode from 'qrcode'
import { getList, addJobSeekerMaterial, updateJobSeekerMaterial, deleteJobSeekerMaterial } from '@/api/jobseekermaterial'
export default {
  name: 'JobSeekerMaterial',
  data() {
    return {
      search: {
        name: '',
        state: '',
        hire_date: '',
        page: 1,
        limit: 10,
        total: 0
      },
      materialList: [],
      addForm: {
        name: ''
      },
      addFormRules: {
        name: [
          { required: true, message: '请输入入职人姓名' }
        ]
      },
      updateMaterialFormRules: {
        apply_job: [{ required: true, message: '请输入入职岗位', trigger: 'blur' }],
        apply_department: [{ required: true, message: '请输入入职部门', trigger: 'blur' }],
        current_job_status: [{ required: true, message: '请选择入职前状态', trigger: 'blur' }],
        join_date: [{ required: true, message: '请输入入职日期', trigger: 'blur' }],
        current_job_pay: [{ required: true, message: '请输入目前薪资', trigger: 'blur' }],
        personal_truename: [{ required: true, message: '请输入姓名', trigger: 'blur' }],
        personal_truename_py: [{ required: true, message: '请输入姓名全拼', trigger: 'blur' }],
        personal_sex: [{ required: true, message: '请输入性别', trigger: 'blur' }],
        personal_birthday: [{ required: true, message: '请输入生日', trigger: 'blur' }],
        personal_nation: [{ required: true, message: '请输入民族', trigger: 'blur' }],
        personal_nation_place: [{ required: true, message: '请输入籍贯', trigger: 'blur' }],
        personal_politics_role: [{ required: true, message: '请输入政治面貌', trigger: 'blur' }],
        personal_education: [{ required: true, message: '请输入学历', trigger: 'blur' }],
        personal_health: [{ required: true, message: '请输入健康状况', trigger: 'blur' }],
        personal_marry_status: [{ required: true, message: '请输入婚姻状况', trigger: 'blur' }],
        personal_email: [{ required: true, message: '请输入电子邮箱', trigger: 'blur' }],
        personal_wechat: [{ required: true, message: '请输入微信号', trigger: 'blur' }],
        personal_idcard_address: [{ required: true, message: '请输入身份证地址', trigger: 'blur' }],
        personal_idcard: [{ required: true, message: '请输入身份证号', trigger: 'blur' }],
        personal_address: [{ required: true, message: '请输入现有效地址', trigger: 'blur' }],
        personal_phone: [{ required: true, message: '请输入移动电话', trigger: 'blur' }],
        emergency_contact_name: [{ required: true, message: '请输入紧急联系人', trigger: 'blur' }],
        emergency_contact_relation: [{ required: true, message: '请输入与本人关系', trigger: 'blur' }],
        emergency_contact_phone: [{ required: true, message: '请输入紧急联系电话', trigger: 'blur' }],
        social_household_type: [{ required: true, message: '请输入户口类别', trigger: 'blur' }]
      },
      updateMaterialForm: {
        id: '',
        apply_job: '',
        apply_department: '',
        current_job_status: '',
        join_date: '',
        current_job_pay: '',
        personal_truename: '',
        personal_truename_py: '',
        personal_sex: '',
        personal_birthday: '',
        personal_nation: '',
        personal_nation_place: '',
        personal_politics_role: '',
        personal_education: '',
        personal_health: '',
        personal_marry_status: '',
        personal_email: '',
        personal_wechat: '',
        personal_idcard_address: '',
        personal_idcard: '',
        personal_address: '',
        personal_phone: '',
        emergency_contact_name: '',
        emergency_contact_relation: '',
        emergency_contact_phone: '',
        social_household_type: '',
        social_insurance_is_pay: '',
        social_insurance_city: '',
        social_insurance_start_date: '',
        social_insurance_end_date: '',
        housing_fund_is_pay: '',
        housing_fund_city: '',
        housing_fund_company: '',
        history_job: [],
        history_education: [],
        history_train: [],
        family_members: [],
        join_channel: '',
        join_channel_name: '',
        inner_recommend_name: '',
        inner_recommend_relation: '',
        family_member_name: '',
        family_member_job: '',
        family_member_relation: ''
      },
      emptyUpdateMaterialForm: {},
      multipleSelection: [],
      dialogAddFormVisible: false,
      dialogUpdateFormVisible: false,
      formLabelWidth: '120px'
    }
  },
  created() {
    this.emptyUpdateMaterialForm = this.updateMaterialForm
  },
  mounted() {
    this.getList()
    this.copyUrlEvent()
  },
  methods: {
    addHistoryJobItem() {
      this.updateMaterialForm.history_job.push({
        time: '',
        company: '',
        job: '',
        leave_reason: '',
        salary: '',
        witness: ''
      })
    },
    deleteHistoryJobItem(item, index) {
      this.updateMaterialForm.history_job.splice(index, 1)
    },
    addHistoryEducationItem() {
      this.updateMaterialForm.history_education.push({
        time: '',
        school: '',
        specialty: '',
        education: '',
        degree: ''
      })
    },
    deleteHistoryEducationItem(item, index) {
      this.updateMaterialForm.history_education.splice(index, 1)
    },
    addHistoryTrainItem() {
      this.updateMaterialForm.history_train.push({
        time: '',
        trainer: '',
        content: '',
        certificate: '',
        certificate_number: ''
      })
    },
    deleteHistoryTrainItem(item, index) {
      this.updateMaterialForm.history_train.splice(index, 1)
    },
    addFamilyMembersItem() {
      this.updateMaterialForm.family_members.push({
        name: '',
        phone: '',
        relation: '',
        unit: '',
        title: ''
      })
    },
    deleteFamilyMembersItem(item, index) {
      this.updateMaterialForm.family_members.splice(index, 1)
    },
    copyUrlEvent() {
      var _this = this
      new Clipboard('.copy-btn').on('success', function(e) {
        _this.$message({
          message: '复制成功',
          type: 'success'
        })
      })
    },
    downloadQrcode(name, url) {
      var _this = this
      const canvas = document.getElementById('baseCanvas')
      QRCode.toCanvas(canvas, url, {
        width: 300,
        height: 300,
        margin: 1
      }, function(error) {
        if (error) console.error(error)
        console.log('success!')
        const cans = document.getElementById('baseCanvas')
        const type = 'png'
        const img_png_src = cans.toDataURL('image/png')
        const imgData = img_png_src.replace(_this._imgType(type), 'image/octet-stream')
        const filename = name + '.' + type
        const save_link = document.createElement('a')
        save_link.href = imgData
        save_link.download = filename

        const event = document.createEvent('MouseEvents')
        event.initEvent('click', true, false)
        save_link.dispatchEvent(event)
      })
    },
    _imgType: function(ty) {
      const type = ty.toLowerCase().replace(/jpg/i, 'jpeg')
      var r = type.match(/png|jpeg|bmp|gif/)[0]
      return 'image/' + r
    },
    preview(id) {
      window.open('http://hr.tanwan.com/index.php?c=JobSeekerMaterial&m=preview&id=' + id)
    },
    exportData() {
      let url = 'http://hr.tanwan.com/index.php?c=JobSeekerMaterial&m=export&name' + this.search.name + '&state=' + this.search.state + '&hire_date=' + this.search.hire_date
      const ids = []
      for (var index in this.multipleSelection) {
        ids.push(this.multipleSelection[index].id)
      }
      if (ids.length > 0) {
        url += '&ids=' + ids.join(',')
      }
      window.open(url)
    },
    handleSelectionChange(val) {
      this.multipleSelection = val
    },
    downloadFiles() {
      if (this.multipleSelection.length < 1) {
        this.$message({
          message: '请先选择一个需要下载的档案',
          type: 'warning'
        })
        return
      }
      var ids = []
      for (var index in this.multipleSelection) {
        ids.push(this.multipleSelection[index].id)
      }
      window.open('http://hr.tanwan.com/index.php?c=JobSeekerMaterial&m=downloadFiles&ids=' + ids.join(','))
    },
    getList() {
      getList(this.search).then(res => {
        this.materialList = res.data.data.data
        this.search.total = res.data.data.total
      }).catch(msg => {
        this.$message({
          message: msg,
          type: 'error'
        })
      })
    },
    addJobSeekerMaterial() {
      this.$refs['addForm'].validate((valid) => {
        if (!valid) {
          console.log('error submit!!')
          return false
        }
        addJobSeekerMaterial(this.addForm).then(res => {
          this.dialogAddFormVisible = false
          this.addForm = {
            name: ''
          }
          this.$message({
            message: '添加成功',
            type: 'success'
          })
          this.getList()
        }).catch(msg => {
          this.$message({
            message: msg,
            type: 'error'
          })
        })
      })
    },
    showUpdateFormDialog(row) {
      if (row.data) {
        this.updateMaterialForm = row.data
        this.updateMaterialForm.housing_fund_is_pay = String(this.updateMaterialForm.housing_fund_is_pay)
        this.updateMaterialForm.social_insurance_is_pay = String(this.updateMaterialForm.social_insurance_is_pay)
      }
      this.updateMaterialForm.id = row.id
      this.dialogUpdateFormVisible = true
    },
    updateJobSeekerMaterialData() {
      this.$refs['updateMaterialForm'].validate((valid) => {
        if (!valid) {
          console.log('error submit!!')
          return false
        }
        if (this.updateMaterialForm.history_education.length < 1) {
          this.$message({
            message: '教育经历不能为空',
            type: 'error'
          })
          return false
        }
        if (this.updateMaterialForm.family_members.length < 1) {
          this.$message({
            message: '家庭状况不能为空',
            type: 'error'
          })
          return false
        }
        updateJobSeekerMaterial({
          id: this.updateMaterialForm.id,
          data: this.updateMaterialForm
        }).then(res => {
          this.$message({
            message: '编辑成功',
            type: 'success'
          })
          this.dialogUpdateFormVisible = false
          this.getList()
        }).catch(msg => {
          this.$message({
            message: msg,
            type: 'error'
          })
        })
      })
    },
    deleteJobSeekerMaterial(id) {
      this.$confirm('确认删除？', '提示').then(() => {
        deleteJobSeekerMaterial({
          'id': id
        }).then(res => {
          this.$message({
            message: '删除成功',
            type: 'success'
          })
          this.getList()
        }).catch(msg => {
          this.$message({
            message: msg,
            type: 'error'
          })
        })
      })
    },
    cancelAddJobSeekerMaterial() {
      this.dialogAddFormVisible = false
      this.addForm = {
        name: ''
      }
    },
    cancelUpdateJobSeekerMaterial() {
      this.dialogUpdateFormVisible = false
      this.updateMaterialForm = this.emptyUpdateMaterialForm
    },
    changePageSize(pageSize) {
      this.search.limit = pageSize
      getList(this.search).then(res => {
        this.materialList = res.data.data.data
        this.search.total = res.data.data.total
      })
    },
    changePage(page) {
      this.search.page = page
      getList(this.search).then(res => {
        this.materialList = res.data.data.data
        this.search.total = res.data.data.total
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
</style>
