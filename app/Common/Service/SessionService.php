<?php
namespace App\Common\Service;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 10:45
 */

use App\Sys\Model\DataBaseModel\SysAdminPermissionModel;
use App\Sys\Model\DataBaseModel\SysAdminRoleModel;
use App\Sys\Model\DataBaseModel\SysAdminRolePermissionModel;


/**
 * Created by PhpStorm.
 * User: zzh
 * Date: 2019/4/23
 * Time: 14:12
 */
class SessionService
{
    /**
     * 静态对象
     * @var SessionService
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return SessionService
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
     * 设置管理员的session
     * @param string $token
     * @param \Illuminate\Database\Eloquent\Model|null|object|static $adminInfo
     */
    public function setAdminLoginSession($token, $adminInfo)
    {
        // 设置session信息
        $_SESSION['token'] = $token;
        $_SESSION['user_info'] = [
            'token' => $token,
            'nickname' => $adminInfo->nickname,
            'username' => $adminInfo->username,
            'id' => $adminInfo->id,
            'role_id' => $adminInfo->role_id
        ];
        $permission = SysAdminRolePermissionModel::instance()->getRolePermission($adminInfo->role_id);
        //组装权限数据
        if ($permission !== false && $permission->permission_list) {
            $permissionItem = SysAdminPermissionModel::instance()->getPermission($permission->permission_list);
            $tree = $this->getTree($permissionItem);
        }

        $permissionInfo = [
            'tree' => $tree ?? '',
            'permission_uri' => array_column((array)$permissionItem, 'uri')
        ];
        // 权限
        $_SESSION['permission'] = $permissionInfo['tree'];
        $_SESSION['permission_uri'] = $permissionInfo['permission_uri'];
        $_SESSION['role_type'] = SysAdminRoleModel::instance()->getRoleType($adminInfo->role_id);
    }

    /**
     * 获取一颗组装好的树
     * @param \Illuminate\Support\Collection $items
     * @return array
     */
    public function getTree($items)
    {
        //格式化好的树
        $tree = [];

        //把数组的key跟id对应上
        $itemsIndex = [];
        foreach ($items as $item) {
            $itemsIndex[$item->id] = $item;
        }
        unset($item);
        foreach ($itemsIndex as $item) {
            if (isset($itemsIndex[$item->pid])) {
                $itemsIndex[$item->pid]->{'children'}[] = $itemsIndex[$item->id];
            } else {
                $tree[] = $itemsIndex[$item->id];
            }
        }
        return $tree;
    }

}
