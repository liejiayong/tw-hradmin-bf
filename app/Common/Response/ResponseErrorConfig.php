<?php
/**
 * 接口返回状态错误码定义
 * User: zzh
 * Date: 2018/10/11
 * Time: 11:30
 */

namespace App\Common\Response;


class ResponseErrorConfig
{
    /**
     * 没有错误
     */
    const NO_ERROR = [
        'errCode' => 0,
        'msg' => '返回请求'
    ];

    /**
     * 缺少必要参数
     */
    const MISSING_PARAM = [
        'errCode' => 1001,
        'msg' => '缺少必要参数'
    ];

    /**
     * 参数错误
     */
    const PARAM_ERROR = [
        'errCode' => 1003,
        'msg' => ''
    ];

    /**
     * 登录过期
     */
    const LOGOUT = [
        'errCode' => 1002,
        'msg' => '登录过期'
    ];

    /**
     * 无权限
     */
    const ACCESS_DENY = [
        'errCode' => 1004,
        'msg' => '无权限'
    ];

    /**
     * 签名不正确
     */
    const SIGN_FAIL = [
        'errCode' => 1005,
        'msg' => '签名不正确'
    ];

    /**
     * 被封禁
     */
    const BLACKLIST = [
        'errCode' => 444,
        'msg' => '用户被封禁'
    ];

    /**
     * 找不到用户
     */
    const NO_USERNAME = [
        'errCode' => 1006,
        'msg' => '找不到用户'
    ];

    /**
     * 密码错误
     */
    const ERR_PASSWORD = [
        'errCode' => 1007,
        'msg' => '密码错误'
    ];
    /**
     * 密码错误
     */
    const NO_TOKEN = [
        'errCode' => 1008,
        'msg' => '生成token失败'
    ];
}
