<?php
/**
 * 接口返回
 * User: zzh
 * Date: 2018/10/11
 * Time: 11:30
 */

namespace App\Common\Response;

class Response
{
    /**
     * 是否已经返回过
     * @var bool
     */
    private static $isAlreadyResponse = null;

    /**
     * 静态对象
     * @var Response
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return Response
     */
    public static function instance()
    {
        if (empty(static::$instance)) {
            self::$isAlreadyResponse = false;
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * 接口数据
     * @var array
     */
    private $item = [];

    /**
     * 增加额外输出数据
     * @param string $key
     * @param $value
     */
    public function setItem($key, $value)
    {
        $this->item[$key] = $value;
    }

    /**
     * 删除额外输出数据
     * @param $Key
     */
    public function deleteItem($Key)
    {
        unset($this->item[$Key]);
    }

    /**
     * 系统默认的错误返回数据
     */
    public function errorResponseData()
    {
        return [
            'code' => ResponseCode::FAILURE,
            'errCode' => ResponseErrorConfig::NO_ERROR['errCode'],
            'msg' => '网络出错，请稍后再试!',
            'data' => []
        ];
    }

    /**
     * 接口输出json数据
     * @param array $response
     */
    public function json($response = [])
    {
        if (self::$isAlreadyResponse !== null && !self::$isAlreadyResponse) {
            $response['debug'] = $response['debug'] ?? [];
            $response['debug'] = array_merge($response['debug'], $this->item);
            $lastResponse = [
                'code' => $response['code'] ?? ResponseCode::FAILURE,
                'errCode' => $response['errCode'] ?? ResponseErrorConfig::NO_ERROR['errCode'],
                'data' => $response['data'] ?? [],
                'msg' => $response['msg'] ?? ResponseErrorConfig::NO_ERROR['msg']
            ];
            if (DEBUG) {
                $lastResponse['debug'] = $response['debug'];
            }
            echo json_encode($lastResponse, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
//        $log = print_r($response, true);
//        file_put_contents("debug.json", $log, FILE_APPEND);
            self::$isAlreadyResponse = true;
            exit(0);
        }
    }

    /**
     * 接口逻辑成功数据
     * @param array $data
     * @param string $msg
     * @param array $errCode
     */
    public function successJson($data = [], $msg = '请求成功', $errCodeConfig = [])
    {
        $this->json([
            'code' => ResponseCode::SUCCESS,
            'errCode' => $errCodeConfig ? $errCodeConfig['errCode'] : (ResponseErrorConfig::NO_ERROR['errCode']),
            'msg' => $errCodeConfig ? $errCodeConfig['msg'] : $msg,
            'data' => $data
        ]);
    }

    /**
     * 接口失败数据
     * @param array $data
     * @param string $msg
     * @param array $errCodeConfig
     */
    public function failJson($data = [], $msg = '请求失败', $errCodeConfig = [])
    {
        $this->json([
            'code' => ResponseCode::FAILURE,
            'errCode' => $errCodeConfig ? ($errCodeConfig['errCode']) : (ResponseErrorConfig::NO_ERROR['errCode']),
            'msg' => $errCodeConfig && ($errCodeConfig['msg'] !== '') ? ($errCodeConfig['msg']) : $msg,
            'data' => $data
        ]);
    }

    /**
     * 自动返回
     * @param $res
     * @param array $data
     */
    public function autoResponse($res, $data = [])
    {
        if ($res) {
            $this->successJson($data);
        } else {
            $this->failJson();
        }
    }

    /**
     * 全局测试varDump
     * @param $data
     */
    public function varDump($data)
    {
        var_dump(...func_get_args());
        self::$isAlreadyResponse = true;
        exit;
    }
}
