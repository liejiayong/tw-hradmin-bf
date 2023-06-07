<?php
/**
 * Redis的Model抽象类
 * User: zzh
 * Date: 2018/10/10
 * Time: 17:28
 */

namespace App\Common\Model\CacheModel;

use Redis;

/**
 * @property Redis redis
 */
abstract class AbstractCacheModel
{
    /**
     * @param $name
     * @return Redis
     */
    public function __get($name)
    {
        if ($name === 'redis') {
            return $this->getRedis('local');
        }
        return null;
    }

    /**
     * 获取Redis实例
     * @param $name
     * @return Redis
     */
    function getRedis($name)
    {
        static $redis_server = [];
        if (isset($redis_server[$name])) {
            return $redis_server[$name];
        }
        if (IS_SERVER) {
            $redis_conf = REDIS_LIST['server'];
        } else {
            $redis_conf = REDIS_LIST[$name];
        }
        $redis_server[$name] = new Redis();
        $redis_server[$name]->connect($redis_conf['host'], $redis_conf['port']);
        $redis_server[$name]->auth($redis_conf['auth']);
        $redis_server[$name]->select($redis_conf['database']);

        return $redis_server[$name];
    }

    /**
     * clone
     */
    private function __clone()
    {
        throw_exception('不允许克隆');
    }
}
