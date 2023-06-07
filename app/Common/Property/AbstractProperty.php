<?php

namespace App\Common\Property;

use App\Common\Response\Response;
use App\Common\Response\ResponseCode;
use stdClass;

abstract class AbstractProperty
{
    /**
     * 设置属性
     * @param array $params
     * @return AbstractProperty
     */
    public function setProperty($params)
    {
        $needParams = get_object_vars($this);
        foreach ($needParams as $key => $value) {
            if (!isset($params[$key]) && $value === NULL) {
                Response::instance()->json([
                    'code' => ResponseCode::FAILURE,
                    'msg' => "抽象属性表类中{$key}属性值不存在"
                ]);
            } else {
                $this->$key = $params[$key] ?? $this->$key;
            }
        }
        return $this;
    }

    /**
     * 生成数组
     * @return array
     */
    public function toArray()
    {
        $result = [];
        $ref = null;
        try {
            $ref = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
        }
        $ownProps = array_filter($ref->getProperties(), function ($property) {
            return $property->class == static::class;
        });

        /** @var \ReflectionProperty $value */
        foreach ($ownProps as $key => $value) {
            $result[$value->getName()] = $this->{$value->getName()};
        }
        return $result;
    }

    /**
     * 生成集合
     * @return stdClass
     */
    public function toObject()
    {
        $collect = new stdClass();
        $ref = null;
        try {
            $ref = new \ReflectionClass(static::class);
        } catch (\ReflectionException $e) {
        }
        $ownProps = array_filter($ref->getProperties(), function ($property) {
            return $property->class == static::class;
        });
        /** @var \ReflectionProperty $value */
        foreach ($ownProps as $key => $value) {
            $collect->{$value->getName()} = $this->{$value->getName()};
        }
        return $collect;
    }
}
