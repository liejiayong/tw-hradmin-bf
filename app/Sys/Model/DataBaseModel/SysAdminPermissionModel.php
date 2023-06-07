<?php

namespace App\Sys\Model\DataBaseModel;

use App\Common\Model\DataBaseModel\AbstractDataBaseModel;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 10:50
 */
class SysAdminPermissionModel extends AbstractDataBaseModel
{
    /**
     * 静态对象
     * @var SysAdminPermissionModel
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return SysAdminPermissionModel
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
        $this->setTable('hd_sys_admin_permission');
    }

    private function __clone()
    {
    }

    /**
     * 获取权限数组(默认获取子权限的父权限)
     * @param string $permissionIdStr 用逗号分隔
     * @return \Illuminate\Support\Collection
     */
    public function getPermission($permissionIdStr)
    {
        return $this->builder->whereIn('id', explode(',', $permissionIdStr))->get();
    }

    /**
     * @param array $condition
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionList($condition = [])
    {
        return $this->getCondition($condition)->get();
    }

    /**
     * @param array $condition 查询条件
     * @return \Illuminate\Database\Query\Builder 查询构造器对象
     */
    protected function getCondition($condition)
    {
        return $this->builder;
    }
}
