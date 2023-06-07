<?php
/**
 * 管理员管理数据接口
 */

namespace App\Sys\Controller;

use App\Common\Response\Response;
use App\Common\Response\ResponseErrorConfig;
use App\Sys\Logic\AdminLogic;

class AdminController extends AdminBaseController
{
    public $unloginAction = ['login'];

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function getAdminList()
    {
        Response::instance()->successJson(AdminLogic::instance()->getAdminList($this->requestData));
    }

    /**
     *
     */
    public function addAdmin()
    {
        $addRes = AdminLogic::instance()->addAdmin($this->requestData);
        if ($addRes) {
            Response::instance()->successJson([], '添加成功');
        } else {
            Response::instance()->failJson([], '添加失败');
        }
    }

    /**
     *
     */
    public function getRoleList()
    {
        Response::instance()->successJson(AdminLogic::instance()->getRoleList($this->requestData));
    }

    /**
     *
     */
    public function addRole()
    {
        $addRes = AdminLogic::instance()->addRole($this->requestData);
        if ($addRes) {
            Response::instance()->successJson([], '添加成功');
        } else {
            Response::instance()->failJson([], '添加失败');
        }
    }

    public function updateRole()
    {
        $updateRes = AdminLogic::instance()->updateRole($this->requestData);
        if ($updateRes) {
            Response::instance()->successJson([], '更新成功');
        } else {
            Response::instance()->failJson([], '更新失败');
        }
    }

    /**
     *
     */
    public function getRoleTypeMap()
    {
        Response::instance()->successJson(AdminLogic::instance()->getRoleTypeMap());
    }

    /**
     *
     */
    public function getRolePermission()
    {
        Response::instance()->successJson(AdminLogic::instance()->getRolePermission($this->requestData));
    }

    /**
     *
     */
    public function getRoleUser()
    {
        Response::instance()->successJson(AdminLogic::instance()->getRoleUser($this->requestData));
    }

    /**
     *
     */
    public function updateUserRole()
    {
        $updateRes = AdminLogic::instance()->updateUserRole($this->requestData);
        if ($updateRes) {
            Response::instance()->successJson([], '更新成功');
        } else {
            Response::instance()->failJson([], '更新失败');
        }
    }

    /**
     *
     */
    public function updateRolePermission()
    {
        $updateRes = AdminLogic::instance()->updateRolePermission($this->requestData);
        if ($updateRes) {
            Response::instance()->successJson([], '更新成功');
        } else {
            Response::instance()->failJson([], '更新失败');
        }
    }

    /**
     *
     */
    public function getAllPermission()
    {
        Response::instance()->successJson(AdminLogic::instance()->getAllPermission());
    }

    /**
     * 登录接口
     * @see string  username  管理员账号|必填
     * @see string  password  密码|必填
     */
    public function login()
    {
        // 获取用户信息并验证
        $token = AdminLogic::instance()->adminLogin($this->requestData['username'], $this->requestData['password']);
        if (!$token) {
            Response::instance()->failJson([], '', ResponseErrorConfig::NO_TOKEN);
        } else {
            Response::instance()->successJson(['token' => $token], '登录成功');
        }
    }

    /**
     * 获取用户信息接口
     * @see string  token  必填
     */
    public function info()
    {
        if (isset($_SESSION['token'], $this->requestData['token']) && $_SESSION['token'] == $this->requestData['token']) {
            Response::instance()->successJson([
                'user_info' => $_SESSION['user_info'],
                'permission' => $_SESSION['permission'],
                'permission_uri' => $_SESSION['permission_uri'],
                'role_type' => $_SESSION['role_type']
            ], '获取成功');
        } else {
            AdminLogic::instance()->adminLogout();
            Response::instance()->failJson([], '', ResponseErrorConfig::LOGOUT);
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        AdminLogic::instance()->adminLogout();
        Response::instance()->successJson([], '退出登录成功');
    }

    public function startAdmin()
    {
        $adminId = $this->requestData['admin_id'];
        $result = AdminLogic::instance()->adminStart($adminId);
        Response::instance()->autoResponse($result);
    }

    public function stopAdmin()
    {
        $adminId = $this->requestData['admin_id'];
        $result = AdminLogic::instance()->adminStop($adminId);
        Response::instance()->autoResponse($result);
    }

    public function changeAdminPassword()
    {
        $res = AdminLogic::instance()->changeAdminPassword(
            $_SESSION['user_info']['id'],
            $this->requestData['old_password'],
            $this->requestData['new_password']
        );
        if (!$res) {
            Response::instance()->failJson([], '修改失败');
        } else {
            Response::instance()->successJson([], '修改成功');
        }
    }
}
