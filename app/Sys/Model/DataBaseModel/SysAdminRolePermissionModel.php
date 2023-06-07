<?php

namespace App\Sys\Model\DataBaseModel;

use App\Common\Model\DataBaseModel\AbstractDataBaseModel;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 10:50
 */
class SysAdminRolePermissionModel extends AbstractDataBaseModel
{
    /**
     * 静态对象
     * @var SysAdminRolePermissionModel
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return SysAdminRolePermissionModel
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
        $this->setTable('hd_sys_admin_role_permission');
    }

    private function __clone()
    {
    }

    /**
     * 根据管理员角色id获取其权限
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
     */
    public function getRolePermission($roleId)
    {
        return $this->builder->where(['role_id' => $roleId])->first();
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
     * @param array $condition 查询条件
     * @return \Illuminate\Database\Query\Builder 查询构造器对象
     */
    protected function getCondition($condition)
    {
        return $this->builder;
    }

    public function createRolePermission($info) {
        return $this->builder->insert($info);
    }

    /**
     * @param $updateInfo
     * @return int
     */
    public function updateRolePermission($updateInfo)
    {
        $builder = $this->builder;
        $id = $updateInfo['id'];
        unset($updateInfo['id']);
        return $builder->where('id', $id)->update($updateInfo);
    }

    /**
     * @param $updateInfo
     * @return int
     */
    public function updateRolePermissionByRoleId($updateInfo)
    {
        $builder = $this->builder;
        return $builder->where('role_id', $updateInfo['role_id'])->update($updateInfo);
    }
}
