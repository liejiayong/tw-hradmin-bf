<?php
/**
 * MySQL的Model抽象类
 * User: zzh
 * Date: 2018/10/10
 * Time: 17:28
 */

namespace App\Common\Model\DataBaseModel;

use App\Common\Response\Response;
use App\Common\Response\ResponseCode;
use App\Common\Object\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Connection;

/**
 * @property Builder builder
 * @property String tableName
 * @property Connection connection
 */
abstract class AbstractDataBaseModel
{
    /**
     * 数据库表名
     * @var string $table
     */
    private $table;

    /**
     * 设置table
     * @param string $table mysql表名
     */
    protected function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * 根据传入值重置表
     * @param $table
     */
    public function resetTable($table){
        $this->setTable($table);
    }

    /**
     * 获取table
     * @return string
     */
    protected function getTable()
    {
        return $this->table;
    }

    /**
     * @param array $condition 查询条件
     * @return \Illuminate\Database\Query\Builder 查询构造器对象
     */
    abstract protected function getCondition($condition);

    /**
     * 批量更新
     * @param array $update
     * @param string $whenField
     * @param string $whereField
     * @return int
     */

    function updateBatch($update, $whenField = 'id', $whereField = 'id')
    {
        $when = [];
        $ids = [];
        foreach ($update as $sets) {
            #　跳过没有更新主键的数据
            if (!isset($sets[$whenField])) continue;
            $whenValue = $sets[$whenField];

            foreach ($sets as $fieldName => $value) {
                #主键不需要被更新
                if ($fieldName == $whenField) {
                    array_push($ids, $value);
                    continue;
                };

                $when[$fieldName][] = "when '{$whenValue}' then '{$value}'";
            }
        }

        #　没有更新的条件id
        if (!$when) return false;

        $builder = $this->builder->whereIn($whereField, $ids);

        #　组织sql
        foreach ($when as $fieldName => &$item) {
            $item = $this->builder->raw("case $whenField " . implode(' ', $item) . ' end ');
        }

        return $builder->update($when);
    }

    /**
     * 根据条件筛选数量
     * @param $condition
     * @return int
     */
    public function getCount($condition)
    {
        unset($condition['page'], $condition['limit']);
        return $this->getCondition($condition)->count();
    }


    /**
     * 获取数据库对象
     * @param $name
     * @return Connection|Builder|string
     */
    public function __get($name)
    {
        if ($name === 'builder') {
            return DB::table($this->table);
        } else if ($name === 'connection') {
            return DB::connection();
        } else if ($name === 'tableName') {
            return $this->table;
        }
        return null;
    }

    /**
     * clone
     */
    private function __clone()
    {
        Response::instance()->json([
            'code' => ResponseCode::FAILURE,
            'msg' => '不允许克隆'
        ]);
    }
}
