<?php
namespace App\Sys\Controller;


use App\Common\Response\Response;
use App\Sys\Logic\JobSeekerMaterialLogic;

class JobSeekerMaterialController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        Response::instance()->successJson(JobSeekerMaterialLogic::instance()->getList($this->requestData));
    }

    public function add()
    {
        $res = JobSeekerMaterialLogic::instance()->add($this->requestData);
        if ($res) {
            Response::instance()->successJson([], '添加成功');
        } else {
            Response::instance()->failJson([], '添加失败');
        }
    }

    public function update()
    {
        $res = JobSeekerMaterialLogic::instance()->update($this->requestData);
        if ($res) {
            Response::instance()->successJson([], '更新成功');
        } else {
            Response::instance()->failJson([], '更新失败');
        }
    }

    public function delete()
    {
        $res = JobSeekerMaterialLogic::instance()->delete($this->requestData);
        if ($res) {
            Response::instance()->successJson([], '删除成功');
        } else {
            Response::instance()->failJson([], '删除失败');
        }
    }

    public function preview()
    {
        $this->UnAutoResponse();

        //获取材料内容
        $info = JobSeekerMaterialLogic::instance()->getInfo($this->requestData);
        $material_content = json_decode($info->data, true);

        //填充
        require(ROOT_DIR . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR ."template_{$info->template_version}.html");
    }

    public function export()
    {
        $this->UnAutoResponse();
        JobSeekerMaterialLogic::instance()->export($this->requestData);
    }

    public function downloadFiles()
    {
        $this->UnAutoResponse();
        JobSeekerMaterialLogic::instance()->downloadFiles($this->requestData);
    }
}
