<?php

namespace App\Common\Middle;

use App\Common\Response\Response;
use App\Common\Response\ResponseErrorConfig;

abstract class AbstractMiddleWare
{
    /**
     * @var array 请求数据修饰
     */
    private $requestAfter = [];

    /**
     * @var array 请求数据默认值
     */
    private $requestDefault = [];

    /**
     * @var array 请求数据必要字段
     */
    private $requestField = [];

    /**
     * @var array 请求数据错误信息
     */
    private $requestErrMsg = [];

    /**
     * 设置输入参数的中间键加工函数
     * @param array $requestAfter
     * @return AbstractMiddleWare
     */
    public function setRequestAfter(array $requestAfter): AbstractMiddleWare
    {
        $this->requestAfter = $requestAfter;
        return $this;
    }

    /**
     * 设置输入参数的默认值
     * @param array $requestDefault
     * @return AbstractMiddleWare
     */
    public function setRequestDefault(array $requestDefault): AbstractMiddleWare
    {
        $this->requestDefault = $requestDefault;
        return $this;
    }

    /**
     * 设置输入参数的key
     * @param array $requestField
     * @return AbstractMiddleWare
     */
    public function setRequestField(array $requestField): AbstractMiddleWare
    {
        $this->requestField = $requestField;
        global $globalRequestField;
        $globalRequestField = $this->requestField;
        return $this;
    }

    /**
     * 设置输入参数的报错信息
     * @param array $requestErrMsg
     * @return AbstractMiddleWare
     */
    public function setRequestErrMsg(array $requestErrMsg): AbstractMiddleWare
    {
        $this->requestErrMsg = $requestErrMsg;
        return $this;
    }

    /**
     * 设置输入参数是否严格模式
     * @param bool $strict
     */
    public function setRequestStrict($strict = true)
    {
        global $globalRequest;
        $request_field = [];
        foreach ($this->requestField as $key => $field_name) {
            if (isset($globalRequest[$field_name]) && $globalRequest[$field_name] !== '') {
                $request_field[] = $field_name;
            } else {
                if (isset($this->requestDefault[$field_name])) {
                    $request_field[] = $field_name;
                } else {
                    if ($strict) {
                        Response::instance()->failJson(
                            [],
                            isset($this->requestErrMsg[$field_name]) ? "{$this->requestErrMsg[$field_name]}不能为空" : "{$field_name}不能为空！",
                            ResponseErrorConfig::PARAM_ERROR
                        );
                    }
                }
            }
            $this->requestField = $request_field;
            global $globalRequestField;
            $globalRequestField = $this->requestField;

        }
    }

    /**
     * 获取http传入的数据
     */
    public function takeMiddleData()
    {
        global $globalRequest;
        $http_data = [];
        foreach ($this->requestField as $key => $field_name) {
            if (isset($globalRequest[$field_name]) && $globalRequest[$field_name] !== '') {
                $http_data[$field_name] = ($globalRequest[$field_name]);
            } else {
                if (isset($this->requestDefault[$field_name])) {
                    $http_data[$field_name] = ($this->requestDefault[$field_name]);
                } else {
                    Response::instance()->failJson(
                        [],
                        isset($this->requestErrMsg[$field_name]) ? "{$this->requestErrMsg[$field_name]}不能为空" : "{$field_name}不能为空!!",
                        ResponseErrorConfig::PARAM_ERROR
                    );
                }
            }
            if (isset($http_data[$field_name]) && isset($this->requestAfter[$field_name])) {
                $http_data[$field_name] = $this->requestAfter[$field_name]($http_data[$field_name]);
            }
            $globalRequest[$field_name] = $http_data[$field_name];
        }
    }

}
