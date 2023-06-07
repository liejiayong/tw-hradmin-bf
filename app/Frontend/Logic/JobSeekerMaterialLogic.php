<?php

namespace App\Frontend\Logic;

use App\Common\Response\Response;
use App\Common\Model\DataBaseModel\JobSeekerMaterialModel;

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

    public function update($requestData)
    {
        //判断token是否正确
        $info = JobSeekerMaterialModel::instance()->getInfoByKey($requestData['token']);
        if (!$info) {
            Response::instance()->failJson([],'无效请求');
        }
        if ($info->data) {
            Response::instance()->failJson([],'记录已存在，请勿重复提交');
        }
        $material_data = json_decode($requestData['data'], true);
        if ($material_data['personal_truename'] != $info->name) {
            Response::instance()->failJson([], '未找到相应的登记表记录，请联系HR');
        }

        $hire_date = $material_data['join_date'];
        $material_data = json_encode($material_data, true);
        return JobSeekerMaterialModel::instance()->update($info->id, [
            'hire_date' => $hire_date,
            'data' => $material_data
        ]);
    }

}
