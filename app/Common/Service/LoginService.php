<?php
/**
 * 基础用户账号数据基础管理服务
 * User: zzh
 * Date: 2018/5/18
 * Time: 14:29
 */

namespace App\Common\Service;


class LoginService
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

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * 生成一个hash密文
     * @param $password   string    明文密码
     * @return string
     */
    public function genHashPwd($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 验证密码正确性
     * @param $password
     * @param $afterHashPassword
     * @return bool
     */
    public function verifyPassword($password, $afterHashPassword)
    {
        return password_verify($password, $afterHashPassword);
    }

    /**
     * 生成一个token
     * @param $uid
     * @param $afterHashPassword
     * @return string
     */
    public function getToken($uid, $afterHashPassword)
    {
        return md5('Session#*(&(*@#(^' . $uid . $afterHashPassword . time() . rand(100, 999) . APP_NAME);
    }

    /**
     * 验证是否已经登录
     * @return bool
     */
    public function checkLogin()
    {
        if (isset($_SESSION['token'], $_SESSION['user_info']) && $_SESSION['token'] && $_SESSION['user_info']) {
            return true;
        }
        return false;
    }
}
