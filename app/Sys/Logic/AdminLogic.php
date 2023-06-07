<?php

namespace App\Sys\Logic;

use App\Common\Response\Response;
use App\Common\Response\ResponseErrorConfig;
use App\Common\Service\LoginService;
use App\Common\Service\SessionService;
use App\Sys\Model\DataBaseModel\SysAdminModel;
use App\Sys\Model\DataBaseModel\SysAdminPermissionModel;
use App\Sys\Model\DataBaseModel\SysAdminRoleModel;
use App\Sys\Model\DataBaseModel\SysAdminRolePermissionModel;
use App\Sys\Property\SysAdmin;
use App\Sys\Property\SysAdminRole;
use App\Sys\Service\RoleMapService;

/**
 * Created by PhpStorm.
 * User: zzh
 * Date: 2019/6/14
 * Time: 10:15
 */
class AdminLogic
{
    /**
     * 静态对象
     * @var AdminLogic
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return AdminLogic
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

    /**
     * @param $condition
     * @return array
     */
    public function getAdminList($condition)
    {
        $adminList = SysAdminModel::instance()->getAdminList($condition)->toArray();
        if (!$adminList) {
            $adminCount = 0;
        } else {
            $roleIdList = array_column($adminList, 'role_id');
            $roleInfoList = RoleMapService::instance()->getNameByRoleId($roleIdList);
            foreach ($adminList as $key => $value) {
                $value->role_id_ch = $roleInfoList[$value->role_id];
            }
            $adminCount = SysAdminModel::instance()->getCount($condition);
        }
        return [
            'data' => $adminList,
            'total' => $adminCount
        ];
    }

    /**
     * @param $addInfo
     * @return bool
     * @throws \Exception
     */
    public function addAdmin($addInfo)
    {
        // 判断账号是否重复
        if (SysAdminModel::instance()->getAdminInfo($addInfo['username'])) {
            Response::instance()->failJson([],"管理员账号已存在");
        }

        $addInfo = (new SysAdmin)->setProperty($addInfo);
        $addRes = SysAdminModel::instance()->addAdmin($addInfo);
        return $addRes;
    }

    /**
     * @param $condition
     * @return array
     */
    public function getRoleList($condition)
    {
        $roleList = SysAdminRoleModel::instance()->getRoleList($condition)->toArray();
        if (!$roleList) {
            $roleCount = 0;
        } else {
            $roleCount = SysAdminRoleModel::instance()->getCount($condition);
        }
        return [
            'data' => $roleList,
            'total' => $roleCount
        ];
    }

    /**
     * @param $addInfo
     * @return bool
     */
    public function addRole($addInfo)
    {
        $addInfo = (new SysAdminRole())->setProperty($addInfo);
        $addRes = SysAdminRoleModel::instance()->addRole($addInfo);
        return $addRes;
    }

    /*
     *
     */
    public function updateRole($updateInfo)
    {
        return SysAdminRoleModel::instance()->updateRole($updateInfo);
    }

    /**
     * 返回角色类型
     * @return array
     */
    public function getRoleTypeMap()
    {
        return RoleMapService::instance()->getAllRoleInfo();
    }

    /**
     * @param $condition
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
     */
    public function getRolePermission($condition)
    {
        return SysAdminRolePermissionModel::instance()->getRolePermission($condition['id']);
    }

    /**
     * @param $condition
     * @return array
     */
    public function getRoleUser($condition)
    {
        $adminList = SysAdminModel::instance()->getAdminList([]);
        $resData = ['all' => [], 'selected' => []];
        foreach ($adminList as $key => $admin) {
            array_push($resData['all'], ['key' => $admin->id, 'label' => $admin->nickname]);
            if ($admin->role_id == $condition['role_id']) {
                array_push($resData['selected'], $admin->id);
            }
        }
        return $resData;
    }

    /**
     * @param $data
     * @return int
     */
    public function updateUserRole($data)
    {
        $updateData = [];
        foreach ($data['admin_id_array'] as $adminId) {
            array_push($updateData, ['id' => $adminId, 'role_id' => $data['role_id']]);
        }
        $result = SysAdminModel::instance()->updateBatch($updateData);
        return $result;
    }

    /**
     * @param $data
     * @return int
     */
    public function updateRolePermission($data)
    {
        $data['role_id'] = $data['role_id'] ?? 0;
        $permission = $data['permission'] ?? '';
        //将权限号重新进行排序
        $permissionArr = explode(',', $permission);
        sort($permissionArr);
        $data['permission'] = implode(',', $permissionArr);
        $updateData['permission_list'] = $data['permission'];
        $updateData['role_id'] = $data['role_id'];

        //不存在则新增
        $exist = SysAdminRolePermissionModel::instance()->getRolePermission($data['role_id']);
        if (!$exist) {
            $res =  SysAdminRolePermissionModel::instance()->createRolePermission($updateData);
        } else {
            $res = SysAdminRolePermissionModel::instance()->updateRolePermissionByRoleId($updateData);
        }

        return $res;
    }

    /**
     * 获取所有菜单
     */
    public function getAllPermission()
    {
        $data = SysAdminPermissionModel::instance()->getPermissionList([]);
        return SessionService::instance()->getTree($data);
    }

    /**
     * 管理员登录
     * @param $username
     * @param $password
     * @return mixed
     */
    public function adminLogin($username, $password)
    {
        // 用户信息
        $adminInfo = SysAdminModel::instance()->getAdminInfo($username);

        // 找不到用户
        if (!$adminInfo) {
            Response::instance()->failJson([], '', ResponseErrorConfig::NO_USERNAME);
        }

        // 验证密码是否正确
        if (!LoginService::instance()->verifyPassword($password, $adminInfo->password)) {
            Response::instance()->failJson([], '', ResponseErrorConfig::ERR_PASSWORD);
        }

        // 验证用户是否被封禁
        if ($adminInfo->status == -1) {
            Response::instance()->failJson([], '', ResponseErrorConfig::BLACKLIST);
        }

        // 生成token
        $token = LoginService::instance()->getToken($adminInfo->id, $adminInfo->password);

        // 设置会话
        SessionService::instance()->setAdminLoginSession($token, $adminInfo);

        SysAdminModel::instance()->updateAdmin([
            'id' => $adminInfo->id,
            'last_login_time' => date('Y-m-d H:i:s')
        ]);

        return $token;
    }

    /**
     * 管理员退出登录
     */
    public function adminLogout()
    {
        // 删除所有 Session 变量
        $_SESSION = array();
        //判断 cookie 中是否保存 Session ID
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        //彻底销毁 Session
        session_destroy();
    }

    public function adminStart($id)
    {
        return SysAdminModel::instance()->updateAdminStatus($id, 1);
    }

    public function adminStop($id)
    {
        return SysAdminModel::instance()->updateAdminStatus($id, -1);
    }

    public function changeAdminPassword($id, $password, $newPassword)
    {
        $adminList = SysAdminModel::instance()->getAdminList(['id' => $id, 'password' => $password])->toArray();
        if($adminList){
            $updateInfo['id'] = $id;
            $updateInfo['password'] = LoginService::instance()->genHashPwd($newPassword);
            return SysAdminModel::instance()->updateAdmin($updateInfo);
        }else{
            Response::instance()->failJson([], '账号失效');
        }
    }
}
