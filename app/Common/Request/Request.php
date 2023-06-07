<?php
/**
 * 接口返回
 * User: zzh
 * Date: 2018/10/11
 * Time: 11:30
 */

namespace App\Common\Request;

class Request
{
    /**
     * 静态对象
     * @var null
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return null|static
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * 获取http传入的数据
     * @param array $field 需要的request值的数组
     * @param bool $isEmpty 是否需要空字符串
     * @return array
     */
    public function takeHttpData($field = ['default'], $isEmpty = false)
    {
        global $globalRequest;
        if ($field == ['default']) {
            global $globalRequestField;
            if($globalRequestField == []){
                return $globalRequest;
            }
            $field = $globalRequestField;
        }

        $http_data = [];
        foreach ($field as $key => $field_name) {
            if (isset($globalRequest[$field_name]) && ($isEmpty || $globalRequest[$field_name] !== '')) {
                $http_data[$field_name] = $globalRequest[$field_name];
            }
        }
        return $http_data;
    }
}
