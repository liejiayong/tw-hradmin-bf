<?php

namespace App\Sys\Service;

use Redis;

class RedisService
{

    //定义单例模式的变量
    private static $instance = null;

    public $redis = null;

    /**
     * @return RedisService|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        if (self::$instance->ping() != '+PONG') {
            throw new Exception('网络异常，请稍候再试~');
        }

        return self::$instance;
    }

    /**
     * RedisService constructor.
     */
    private function __construct()
    {
        $this->redis = new Redis();
        $redisConfig = IS_SERVER ? REDIS_LIST['server'] : REDIS_LIST['local'];
        $result = $this->redis->connect($redisConfig['host'], $redisConfig['port'], 3.5);
        if ($result === false) {
            throw new Exception('redis connect error');
        }

        $this->redis->auth($redisConfig['auth']);
    }

    //禁止克隆对象
    private function __clone()
    {

    }

    public function select($index)
    {
        if (!is_int($index)) {
            return false;
        }

        $this->redis->select($index);

        return $this::getInstance();
    }

    public function ping()
    {
        try {
            return $this->redis->ping();
        } catch (\Exception $e) {

        }
        return "HDRED Connection failed！";
    }

    /**
     * 关闭事务
     */
    public function close()
    {
        $this->redis->close();
    }

    /**
     * WATCH命令可以监控一个或多个键，一旦其中有一个键被修改（或删除），之后的事务就不会执行。监控一直持续到EXEC命令（事务中的命令是在EXEC之后才执行的，所以在MULTI命令后可以修改WATCH监控的键值）
     */
    public function watch($name)
    {
        $this->redis->watch($name);
    }

    /**
     * 事务开启
     */
    public function multi()
    {
        return $this->redis->multi();
    }

    /**
     *  事务执行，事务开启返回对象执行此操作
     */
    public function exec()
    {
        return $this->redis->exec();
    }

    /**
     * redis 键值对生存时间
     * @param string $name
     * @param int $expire
     * @return int
     */
    public function expire($name = '', $expire = 60)
    {
        $this->redis->expire($name, $expire);
        $backContent = $this->redis->TTL($name);
        return $backContent;
    }

    /**
     * 设置键值对
     * @param string $name
     * @param string $content
     * @param int $expire 默认60s
     * @return int
     */
    public function set($name = '', $content = '', $expire = 60)
    {
        $this->redis->set($name, $content);
        if ($expire) $this->redis->expire($name, $expire);
        $backContent = $this->redis->TTL($name);
        return $backContent;
    }

    /**
     * 获取键值对的值
     * @param string $name
     * @return mixed
     */
    public function get($name = '')
    {
        return $this->redis->get($name);
    }

    /**
     * 删除键值对
     * @param string $name
     * @return int
     */
    public function del($name = '')
    {
        return $this->redis->del($name);
    }

    /**
     * 获取键值对的剩余生存时间
     * @param string $name
     * @return int
     */
    public function ttl($name = '')
    {
        return $this->redis->ttl($name);
    }

    /**
     * 剔除键值的过期时间，持久化
     * @param string $name
     * @return int
     */
    public function presist($name = '')
    {
        return $this->redis->persist($name);
    }

    /**
     * 键值对数值自增
     * @param string $name
     * @param int $content
     * @param int $expire
     * @return int
     */
    public function incrby($name = '', $content = 1, $expire = 60)
    {
        $backContent = $this->redis->incrBy($name, $content);
        if ($expire) $this->redis->expire($name, $expire);
        return $backContent;
    }

    /**
     * 键值对数值自减
     * @param string $name
     * @param int $content
     * @param int $expire
     * @return int
     */
    public function decrby($name = '', $content = -1, $expire = 60)
    {
        $backContent = $this->redis->decrBy($name, $content);
        if ($expire) $this->redis->expire($name, $expire);
        return $backContent;
    }

    /**
     * 左插入队列
     * @param string $name
     * @param string $content
     * @param int $expire
     * @return bool|int
     */
    public function lpush($name = '', $content = '', $expire = 60)
    {
        $res = $this->redis->lPush($name, $content);
        if ($res) {
            if ($expire) $this->redis->expire($name, $expire);
            $backContent = $this->redis->ttl($name);
            return $backContent;
        }
        return false;
    }

    /**
     * 左插入所有数据
     * @param string $name
     * @param array $contentArray
     * @param int $expire
     * @return boolean
     */
    public function lPushAll($name, $contentArray, $expire = 60)
    {
        $res = $this->redis->lPush($name, ...$contentArray);
        if ($res) {
            $this->redis->expire($name, $expire);
            return true;
        }
        return false;
    }

    /**
     * 右插入队列
     * @param string $name
     * @param string $content
     * @param int $expire
     * @return bool|int
     */
    public function rpush($name = '', $content = '', $expire = 60)
    {
        $res = $this->redis->rPush($name, $content);
        if ($res) {
            if ($expire) $this->redis->expire($name, $expire);
            $backContent = $this->redis->ttl($name);
            return $backContent;
        }
        return false;
    }

    /**
     * 左弹出队列的第一个元素
     * @param string $name
     * @return string
     */
    public function lpop($name = '')
    {
        return $this->redis->lPop($name);
    }

    /**
     * 右弹出队列的第一个元素
     * @param string $name
     * @return string
     */
    public function rpop($name = '')
    {
        return $this->redis->rPop($name);
    }

    public function lrange($key, $start, $end)
    {
        return $this->redis->lrange($key, $start, $end);
    }

    //-1表示倒数第一个
    public function lrang($name = '', $start = 0, $end = -1)
    {
        return $this->redis->lrange($name, $start, $end);
    }

    public function lrem($name = '', $content = '', $number = 0)
    {
        return $this->redis->lrem($name, $content, $number);
    }

    public function lset($name = '', $content, $index = 0)
    {
        return $this->redis->lSet($name, $index, $content);
    }

    //集合-set
    public function sadd($name = '', $content = '', $expire = 60)
    {
        $res = $this->redis->sAdd($name, $content);
        if ($res) {
            if ($expire) $this->redis->expire($name, $expire);
            $backContent = $this->redis->ttl($name);
            return $backContent;
        }
        return false;
    }

    public function sCard($name)
    {
        return $this->redis->sCard($name);
    }

    //排序返回
    public function sort($name = '')
    {
        return $this->redis->sort($name);
    }


    //随机获取并移除
    public function spop($name = '')
    {
        return $this->redis->sPop($name);
    }

    // 移除集合中一个或多个成员
    public function srem($key, ...$member)
    {
        return $this->redis->sRem($key, ...$member);
    }

    // 有序集合
    public function zadd($name = '', $val = '', $score = 0, $expire = 60)
    {
        $res = $this->redis->zAdd($name, $score, $val);
        if ($res) {
            if ($expire) $this->redis->expire($name, $expire);
            $backContent = $this->redis->ttl($name);
            return $backContent;
        }
        return false;
    }


    /**
     * 返回有序集合由大到小 相同分数默认是靠前 如果需求要后来者靠后，需要做正相关处理(倒序)
     * @param string $name 有序集合的名字
     * @param int $start 排序的开始下标
     * @param int $end 排序的结束下标
     * @param bool $withScore
     * @return mixed
     */
    public function zrevrange($name = '', $start = 0, $end = -1, $withScore = true)
    {
        return $this->redis->zRevRange($name, $start, $end, $withScore);

    }


    /**
     * 返回有序集合由大到小 相同分数默认是靠前 如果需求要后来者靠后，需要做正相关处理(正序)
     * @param string $name
     * @param int $start
     * @param int $end
     * @param bool $withScore
     * @return mixed
     */
    public function zrange($name = '', $start = 0, $end = -1, $withScore = true)
    {
        return $this->redis->zRange($name, $start, $end, $withScore);
    }

    // 获取指定value的排名(倒序)
    public function zrevrank($name, $value)
    {
        return $this->redis->zRevRank($name, $value);
    }

    // 获取指定value的排名(正序)
    public function zrank($name, $value)
    {
        return $this->redis->zRank($name, $value);
    }

    /**
     * 增加一个指定有序集合的键值对的值
     * @param string $name
     * @param int $addScore
     * @param string $value
     * @return int 增加后的分数
     */
    public function zincrby($name, $addScore = 0, $value)
    {
        return $this->redis->zIncrBy($name, $addScore, $value);
    }

    /**
     * 计算集合中元素的数量
     * @param $name
     * @return int
     */
    public function zCard($name)
    {
        return $this->redis->zCard($name);
    }

    public function zrem($key, ...$member)
    {
        return $this->redis->zRem($key, ...$member);
    }

    /**
     * 获取哈希的值
     * @param $hTable
     * @param $hKey
     * @return string
     */
    public function hget($hTable, $hKey)
    {
        return $this->redis->hGet($hTable, $hKey);
    }

    /**
     * 设置哈希的值
     * @param string $hTable
     * @param string $hKey
     * @param string $hValue
     * @return string
     */
    public function hset($hTable, $hKey, $hValue)
    {
        return $this->redis->hSet($hTable, $hKey, $hValue);
    }

    /**
     * 删除哈希表字段
     * @param $hTable
     * @param $hKey
     * @return bool|int
     */
    public function hdel($hTable, $hKey)
    {
        return $this->redis->hDel($hTable, $hKey);
    }

    /**
     * 哈希中某个值自增
     * @param $hTable
     * @param $hKey
     * @param $hValue
     */
    public function hincrby($hTable, $hKey, $hValue)
    {
        $this->redis->hIncrBy($hTable, $hKey, $hValue);
    }

    public function hLen($name = '')
    {
        return $this->redis->hLen($name);
    }

    /**
     * 返回队列的大小
     * @param string $name
     * @return mixed
     */
    public function lsize($name = '')
    {
        return $this->redis->lSize($name);
    }

    /**
     * 返回队列的长度
     * @param string $name
     * @return int
     */
    public function lLen($name = '')
    {
        return $this->redis->lLen($name);
    }

    public function lget($name = '', $index = 0)
    {
        return $this->redis->lGet($name, $index);
    }

    /**
     * redis统计信息
     * @return array
     */
    public function info()
    {
        return $this->redis->info();
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function type($key)
    {
        return $this->redis->type($key);
    }

    public function sScan($key, $iterator, $pattern = '', $count = 1000)
    {
        return $this->redis->sScan($key, $iterator, $pattern, $count);
    }

    public function zScan($key, $iterator, $pattern = '', $count = 1000)
    {
        return $this->redis->zScan($key, $iterator, $pattern, $count);
    }

    public function hScan($key, $iterator, $pattern = '', $count = 1000)
    {
        return $this->redis->hScan($key, $iterator, $pattern, $count);
    }

    /**
     * 析构关闭连接
     */
    public function __destruct()
    {
        $this->close();
        $this->redis = null;
        unset($this->redis);
    }
}
