<?php

namespace App\Sys\Model\DataBaseModel;

use App\Common\Model\DataBaseModel\AbstractDataBaseModel;
use App\Common\Property\AbstractProperty;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 11:05
 */
class SysAdminRoleModel extends AbstractDataBaseModel
{
    /**
     * 静态对象
     * @var SysAdminRoleModel
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return SysAdminRoleModel
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
        $this->setTable('hd_sys_admin_role');
    }

    private function __clone()
    {
    }

    /**
     * 获取角色类型
     * @param $id
     * @return int
     */
    public function getRoleType($id)
    {
        $role = $this->builder
            ->where('id', $id)
            ->first(['type']);
        return intval($role->type);
    }

    /**
     * 根据条件筛选
     * @param $condition
     * @return \Illuminate\Support\Collection
     */
    public function getRoleList($condition = [])
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

    /**
     * @param AbstractProperty $addInfo
     * @return bool
     */
    public function addRole($addInfo)
    {
        $addInfo = $addInfo->toArray();
        unset($addInfo['id']);
        $builder = $this->builder;
        $insertRes = $builder->insert($addInfo);
        return $insertRes;
    }

    /**
     * @param $updateInfo
     * @return int
     */
    public function updateRole($updateInfo)
    {
        $builder = $this->builder;
        $id = $updateInfo['id'];
        unset($updateInfo['id']);
        return $builder->where('id', $id)->update($updateInfo);
    }
}
