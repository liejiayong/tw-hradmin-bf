<?php

namespace App\Sys\Model\DataBaseModel;

use App\Common\Model\DataBaseModel\AbstractDataBaseModel;
use App\Common\Property\AbstractProperty;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 10:21
 */
class SysAdminModel extends AbstractDataBaseModel
{
    /**
     * 静态对象
     * @var SysAdminModel
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return SysAdminModel
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
        $this->setTable('hd_sys_admin');
    }

    private function __clone()
    {
    }

    /**
     * 根据管理员账号获取管理员数据
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getAdminInfo($username)
    {
        return $this->builder->where(['username' => $username])->first();
    }

    /**
     * @param array $condition 查询条件
     * @return \Illuminate\Database\Query\Builder 查询构造器对象
     */
    protected function getCondition($condition)
    {
        $builder = $this->builder;
        if (isset($condition['role_id']) && $condition['role_id'] !== 'none' && $condition['role_id'] !== '') {
            $builder->where(['role_id' => $condition['role_id']]);
        }
        return $builder;
    }

    /**
     * 根据条件筛选
     * @param $condition
     * @return \Illuminate\Support\Collection
     */
    public function getAdminList($condition)
    {
        return $this->getCondition($condition)->get();
    }

    /**
     * 添加一个管理员
     * @param AbstractProperty $addInfo
     * @return bool
     */
    public function addAdmin($addInfo)
    {
        $addInfo = $addInfo->toArray();
        unset($addInfo['id']);
        unset($addInfo['create_time']);
        unset($addInfo['update_time']);
        $builder = $this->builder;
        $insertRes = $builder->insert($addInfo);
        return $insertRes;
    }

    /**
     * 修改管理员状态（1：正常，-1：封禁）
     * @param $id
     * @param $status
     * @return int
     */
    public function updateAdminStatus($id, $status)
    {
        $builder = $this->builder;
        $updateRes = $builder->where('id', '=', $id)->update(['status' => $status]);
        return $updateRes;
    }

    public function updateAdmin($updateInfo)
    {
        $builder = $this->builder;
        $id = $updateInfo['id'];
        unset($updateInfo['id']);
        return $builder->where('id', $id)->update($updateInfo);
    }
}
