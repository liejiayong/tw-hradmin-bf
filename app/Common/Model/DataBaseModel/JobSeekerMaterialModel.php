<?php

namespace App\Common\Model\DataBaseModel;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 10:21
 */
class JobSeekerMaterialModel extends AbstractDataBaseModel
{
    /**
     * 静态对象
     * @var JobSeekerMaterialModel
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return JobSeekerMaterialModel
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
        $this->setTable('job_seeker_material');
    }

    private function __clone()
    {
    }

    /**
     * @param array $condition 查询条件
     * @return \Illuminate\Database\Query\Builder 查询构造器对象
     */
    protected function getCondition($condition)
    {
        $builder = $this->builder;

        if (isset($condition['name']) && $condition['name']) {
            $builder->where('name', 'like', '%' . $condition['name'] . '%');
        }
        if (isset($condition['state']) && $condition['state'] !== '') {
            if ($condition['state'] == 1) {
                $builder->where('data', '!=','');
            } else {
                $builder->where('data', '=','');
            }
        }
        if (isset($condition['hire_date']) && $condition['hire_date']) {
            if (is_string($condition['hire_date'])) {
                $condition['hire_date'] = explode(',', $condition['hire_date']);
            }
            $builder->whereBetween('hire_date', $condition['hire_date']);
        }
        if (isset($condition['ids']) && $condition['ids'] !== '') {
            $builder->whereIn('id', $condition['ids']);
        }

        //设置权限
        if (isset($_SESSION['user_info']) && $_SESSION['user_info']['role_id'] != 1) {
            $builder->where('creator_id', $_SESSION['user_info']['id']);
        }

        if (isset($condition['page']) && isset($condition['limit'])) {
            $builder->forPage($condition['page'], $condition['limit']);
        }

        return $builder->orderBy('id', 'desc');
    }

    public function getList($condition)
    {
        return $this->getCondition($condition)->get();
    }

    public function getInfo($id)
    {
        return $this->builder->where(['id' => $id])->first();
    }


    public function getInfoByKey($key)
    {
        return $this->builder->where(['key' => $key])->first();
    }

    public function add($data)
    {
        return $this->builder->insert($data);
    }

    public function update($id, $updateData)
    {
        $where = [
            'id' => $id,
        ];
        //设置权限
        if (isset($_SESSION['user_info']) && $_SESSION['user_info']['role_id'] != 1) {
            $where['creator_id'] = $_SESSION['user_info']['id'];
        }

        return $this->builder->where($where)->update($updateData);
    }

    public function delete($id)
    {
        $where = [
            'id' => $id,
        ];
        //设置权限
        if (isset($_SESSION['user_info']) && $_SESSION['user_info']['role_id'] != 1) {
            $where['creator_id'] = $_SESSION['user_info']['id'];
        }

        return $this->builder->where($where)->delete();
    }
}
