<?php

namespace App\Common\Controller;

use App\Common\Request\Request;
use App\Common\Response\Response;

abstract class AbstractController
{
    /**
     * http访问时间
     * @var int $httpRequestTime
     */
    protected $httpRequestTime;

    /**
     * 是否自动返回数据
     * @var bool
     */
    private $autoResponse = true;

    /**
     * http的请求数据
     * @var array $requestData
     */
    public $requestData = [];

    /**
     * http返回的数据
     * @var array $responseData
     */
    private $responseData = [];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        //初始化http请求时间
        $this->httpRequestTime = getRequestTime();
        //初始化http请求数据
        $this->requestData = Request::instance()->takeHttpData();
        //初始化http请求数据
        $this->responseData = Response::instance()->errorResponseData();
    }

    /**
     * 设置新的的http请求数据
     * @param array $field
     */
    public function setRequestData($field)
    {
        $this->requestData = Request::instance()->takeHttpData($field, true);
    }

    /**
     * 设置的http返回数据
     * @param array $data
     */
    public function setResponseData($data)
    {
        $this->responseData = $data;
        exit;
    }

    /**
     * 设置不自动返回数据
     */
    public function UnAutoResponse()
    {
        $this->autoResponse = false;
    }

    /**
     * BaseController destructor.
     */
    public function __destruct()
    {
        if ($this->autoResponse) {
            Response::instance()->json($this->responseData);
        }
    }
}
