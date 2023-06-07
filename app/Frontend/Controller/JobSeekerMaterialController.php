<?php

namespace App\Frontend\Controller;

use App\Common\Response\Response;
use App\Frontend\Logic\JobSeekerMaterialLogic;

class JobSeekerMaterialController extends BaseController
{
    public function template(){
        if (!isset($this->requestData['version'])) {
            header("Http/1.1 404 Not Found");
            die;
        }

        $this->display("jobseekermaterial.template_" . (int)$this->requestData['version']);
    }

    public function submit()
    {
        //检查, todo::参数过滤
        $res = JobSeekerMaterialLogic::instance()->update($this->requestData);
        if ($res) {
            Response::instance()->successJson([], '提交成功');
        } else {
            Response::instance()->failJson([], '保存失败');
        }
    }
}
