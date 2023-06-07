<?php

namespace App\Sys\Logic;

use App\Common\Response\Response;
use App\Common\Model\DataBaseModel\JobSeekerMaterialModel;
use App\Sys\Service\ExcelService;

/**
 * Created by PhpStorm.
 * User: zzh
 * Date: 2019/6/14
 * Time: 10:15
 */
class JobSeekerMaterialLogic
{
    /**
     * 静态对象
     * @var JobSeekerMaterialLogic
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return JobSeekerMaterialLogic
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public function getInfo($requestData)
    {
        return JobSeekerMaterialModel::instance()->getInfo($requestData['id']);
    }

    public function getList($requestData)
    {
        $list = JobSeekerMaterialModel::instance()->getList($requestData)->toArray();
        if (!$list) {
            $count = 0;
        } else {
            foreach ($list as $key => $value) {
                $value->url = 'http://hr.tanwan.com/index.php?p=frontend&c=JobSeekerMaterial&m=template&token=' . $value->key . '&version=' . $value->template_version;
                $value->hire_date = $value->hire_date == '0000-00-00 00:00:00' ? '' : date('Y-m-d' , strtotime($value->hire_date));
                $value->data = json_decode($value->data, true);
                $value->data = $value->data ?: '';
            }
            $count = JobSeekerMaterialModel::instance()->getCount($requestData);
        }

        return [
            'data' => $list,
            'total' => $count
        ];
    }

    //新增记录，根据用户姓名生成唯一key（处理同名） md5(姓名+ 当前时间戳),
    public function add($requestData)
    {
        $key = md5($requestData['name'] . time());
        $record = JobSeekerMaterialModel::instance()->getInfoByKey($key);
        if ($record) {
            Response::instance()->failJson([], "唯一key已存在，请重新生成");
        }
        $addInfo = [
            'name' => $requestData['name'],
            'key' => $key,
            'creator_id' => $_SESSION['user_info']['id']
        ];
        return JobSeekerMaterialModel::instance()->add($addInfo);
    }

    public function update($requestData)
    {
        $update_data = [];
        if (isset($requestData['data']) && $requestData['data']) {
            //设置权限
            if (isset($_SESSION['user_info']) && $_SESSION['user_info']['role_id'] != 1) {
                Response::instance()->failJson([], '暂无权限操作');
            }
            $update_data['data'] = json_encode($requestData['data'], true);
            $update_data['hire_date'] = $requestData['data']['join_date'];
        }
        if (empty($update_data)) {
            Response::instance()->failJson([], "更新内容为空");
        }

        return JobSeekerMaterialModel::instance()->update($requestData['id'], $update_data);
    }

    public function delete($requestData)
    {
        $info = JobSeekerMaterialModel::instance()->getInfo($requestData['id']);
        if ($info->data) {
            Response::instance()->failJson([], '无法删除已填写的登记表');
        }

        return JobSeekerMaterialModel::instance()->delete($requestData['id']);
    }

    public function export($requestData)
    {
        if (isset($requestData['ids'])) {
            $requestData['ids'] = explode(',', $requestData['ids']);
        }
        $list = $this->getList($requestData)['data'];
        $export_list = [
            ['入职部门', '入职岗位', '入职时间', '身份证号', '姓名', '姓名全拼', '民族', '政治面貌', '婚姻状况', '学历', '户籍性质', '籍贯', '身份证地址', '联系地址', '联系电话', '紧急联系人',
                '紧急联系人电话', '与本人关系', '毕业学校', '专业', '毕业时间', '上家工作单位', '上家工作职务', '上家工作薪资', '微信号', '个人邮箱', '入职渠道']
        ];
        foreach ($list as $value) {
            if (!$value->data) continue;
            $material_content = $value->data;
            $export_item = [
                $material_content['apply_department'],
                $material_content['apply_job'],
                $value->hire_date,
                $material_content['personal_idcard'],
                $material_content['personal_truename'],
                $material_content['personal_truename_py'] ??  '',
                $material_content['personal_nation'],
                $material_content['personal_politics_role'],
                $material_content['personal_marry_status'],
                $material_content['personal_education'],
                $material_content['social_household_type'],
                $material_content['personal_nation_place'],
                $material_content['personal_idcard_address'],
                $material_content['personal_address'],
                $material_content['personal_phone'],
                $material_content['emergency_contact_name'],
                $material_content['emergency_contact_phone'],
                $material_content['emergency_contact_relation'],
                $material_content['history_education'][0]['school'] ?? '',
                $material_content['history_education'][0]['specialty'] ?? '',
                isset($material_content['history_education'][0]['time']) ? explode('-',  $material_content['history_education'][0]['time'])[1] : '',
                $material_content['history_job'][0]['company'] ?? '',
                $material_content['history_job'][0]['job'] ?? '',
                $material_content['history_job'][0]['salary'] ?? '',
                $material_content['personal_wechat'],
                $material_content['personal_email'],
            ];
            if (isset($material_content['job_channel'])) {
                if ($material_content['job_channel'] == '互联网') {
                    $export_item[] = $material_content['job_channel'] . "（{$material_content['job_channel_web']}）";
                } elseif ($material_content['job_channel'] == '其他') {
                    $export_item[] = $material_content['job_channel'] . "（{$material_content['job_channel_other']}）";
                } else {
                    $export_item[] = $material_content['job_channel'];
                }
            }
            $export_list[] = $export_item;
        }

        ExcelService::instance()->downExcel('入职人员信息登记表', $export_list);
    }

    public function downloadFiles($requestData)
    {
        set_time_limit(0);
        $ids = explode(',', $requestData['ids']);
        $list = JobSeekerMaterialModel::instance()->getList(['ids' => $ids, 'state' => 1]);

        $path = RUNTIME_DIR . '/' .  date('Y-m-d') . '/' . $_SESSION['user_info']['id'] . '_' . time();
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            Response::instance()->failJson([], "创建临时目录失败");
        }

        //打包
        $zip = new \ZipArchive();
        $zipFile = $path . '/tmp.zip';
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            Response::instance()->failJson([], "压缩文件创建失败");
        }

        foreach ($list as $item) {
            $material_content = json_decode($item->data, true);

            $htmlFile = $path . '/' . $item->id . '.html';
            $pdfFile = $path . '/' . $item->id . '.pdf';

            ob_start();
            //填充
            require(ROOT_DIR . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR ."template_{$item->template_version}.html");
            file_put_contents($htmlFile, ob_get_contents());
            ob_end_clean();
            exec("wkhtmltopdf {$htmlFile} {$pdfFile} > /dev/null 2>>/data/log/wkhtmltopdf_error.log", $res_arr, $res_code);
            if ($res_code !== 0) {
                $zip->close();
                die("html 转 pdf 执行失败，当前记录id：" . $item->id);
            }
            $zip->addFile($pdfFile, $item->id . '.pdf');
        }
        $zip->close();

        header('Content-Type: application/octpdfet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Type:application/zip');
        header('Content-disposition: attachment; filename=data.zip');
        header('Content-length: ' . filesize($zipFile));
        readfile($zipFile);
    }
}
