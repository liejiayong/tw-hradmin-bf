<?php

namespace App\Common\Model\CacheModel;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/18
 * Time: 10:06
 */
class WechatAuthCache extends AbstractCacheModel
{
    /**
     * 静态对象
     * @var WechatAuthCache
     */
    protected static $instance = null;

    /**
     * 获取实例
     * @return WechatAuthCache
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
     * AccessToken的缓存key
     * @return string
     */
    private function accessTokenKey($type, $appId)
    {
        return "tw_wechat_{$type}_access_token_{$appId}";
    }

    /**
     * 根据类型来设置AccessToken的缓存
     * @param string $appId
     * @param string $type
     * @param string $value
     */
    public function setAccessToken($appId, $type, $value)
    {
        $this->redis->set($this->accessTokenKey($type, $appId), $value, 7000);
    }

    /**
     * 根据类型来设置AccessToken的缓存
     * @param string $appId
     * @param string $type
     * @return string
     */
    public function getAccessToken($appId, $type)
    {
        return $this->redis->get($this->accessTokenKey($type, $appId));
    }
}
