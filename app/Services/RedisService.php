<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    private static $_instance = null;

    private function __construct()
    {
        $client          = Redis::connection('default')->client();
        self::$_instance = $client;
    }

    public static function getContent()
    {
        if (! self::$_instance) {
            new self;
        }

        return self::$_instance;
    }

    public static function lock($key, $ex)
    {
        $redis  = self::getContent();
        $result = $redis->set($key, 'true', ['NX', 'EX' => $ex]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 采用螺旋锁方式
     * 上锁直到成功或者获取锁时间超时.
     * @param $key
     * @param $ttl
     * @param int $timeout_for_get_lock_sec
     * @return bool
     */
    public static function getLockWithTimeout($key, $ttl = 60, $timeout_for_get_lock_sec = 600)
    {
        $timeout_us    = $timeout_for_get_lock_sec * 100 * 10000;
        $time_sleep_us = 10 * 10000;
        while (! ($locked = self::lock($key, $ttl)) && $timeout_us > 0) {
            usleep($time_sleep_us);
            $timeout_us -= $time_sleep_us;
        }

        return $locked;
    }

    /**
     * 采用螺旋锁方式
     * 再次上锁，直到成功
     * @param $key
     * @param $ttl
     * @return bool
     */
    public static function lockAgainAfterTheLockIsFree($key, $ttl = 180)
    {
        $time_sleep_us = 10 * 10000;
        while (! ($locked = self::lock($key, $ttl))) {
            usleep($time_sleep_us);
        }

        return $locked;
    }

    /**
     * 获取锁执行代码
     * 自动释放锁
     * @param $key
     * @param callable $call
     * @param array $params
     * @return false|mixed
     * @throws \Throwable
     */
    public static function executeWithLock($key, callable $call, $params = [])
    {
        self::lockAgainAfterTheLockIsFree($key);
        try {
            return call_user_func_array($call, $params);
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            self::del($key);
        }
    }

    public static function del($key)
    {
        $redis = self::getContent();

        return $redis->del($key);
    }

    public static function lPush($key, $value)
    {
        $redis = self::getContent();

        return $redis->lPush($key, $value);
    }

    public static function lPop($key)
    {
        $redis = self::getContent();

        return $redis->Lpop($key);
    }

    public static function len($key)
    {
        $redis = self::getContent();

        return $redis->lLen($key);
    }

    public static function range($key)
    {
        $redis = self::getContent();

        return $redis->lRange($key, 0, -1);
    }

    public static function setValue($key, $value)
    {
        $redis = self::getContent();

        return $redis->set($key, $value);
    }

    public static function exists($key)
    {
        $redis = self::getContent();

        return $redis->exists($key);
    }

    public static function getValue($key)
    {
        $redis = self::getContent();

        return $redis->get($key);
    }

    public static function ttlKey($key)
    {
        $redis = self::getContent();

        return $redis->ttl($key);
    }

    public static function lrem($key, $value, $count = 0)
    {
        $redis = self::getContent();

        return $redis->lRem($key, $value, $count);
    }

    public static function setEx($key, $time, $value)
    {
        $redis = self::getContent();

        return $redis->setex($key, $time, $value);
    }

    public static function incrValue($key, $value)
    {
        $redis = self::getContent();

        return $redis->incrBy($key, $value);
    }

    public static function zincrValue($key, $value, $member)
    {
        $redis = self::getContent();

        return $redis->zIncrBy($key, $value, $member);
    }

    public static function expireValue($key, $time)
    {
        $redis = self::getContent();
        $redis->expire($key, $time);
    }
}
