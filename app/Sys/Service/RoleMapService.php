<?php

namespace App\Sys\Service;

use App\Sys\Model\DataBaseModel\SysAdminRoleModel;

/**
 * Created by PhpStorm.
 * User: zzh
 * Date: 2019/4/23
 * Time: 14:12
 */
class RoleMapService
{
    /**
     * 静态对象
     * @var RoleMapService
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return RoleMapService|static
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
     * 根据RoleId换取对应的名字
     * @param array $roleIdList
     * @return array
     */
    public function getNameByRoleId($roleIdList)
    {
        $roleList = $this->getAllRoleInfo();
        $roleMap = [];
        foreach ($roleIdList as $key => $value) {
            $roleMap[$value] = $roleList[$value]['name'] ?? '';
        }
        return $roleMap;
    }

    /**
     * 获取所有公众号列表
     * @return array
     */
    public function getAllRoleInfo()
    {
        $roleDataBaseList = SysAdminRoleModel::instance()->getRoleList()->toArray();
        foreach ($roleDataBaseList as $key => $value) {
            $roleList[$value->id] = (array)$value;
        }
        return $roleList;
    }

}
