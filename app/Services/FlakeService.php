<?php

namespace App\Services;

class FlakeService
{
    private static $lastTimestamp = 0;

    private static $lastSequence = 0;

    private static $sequenceMask = 4095;

    private static $twepoch = 1631181534;

    public const prefix = 'flake';

    public static function generate($dataCenterID = 0, $workerID = 0)
    {
        // 31bit timestamp  + 12bit

        $timestamp = self::timeGen();

        while (($sequence = self::sequence($timestamp)) >= self::$sequenceMask) {
            usleep(1);
            $timestamp = self::timeGen();
        }

        $snowFlakeId = (($timestamp - self::$twepoch) << 12) | $sequence;

        return $snowFlakeId;
    }

    public static function unmake($snowFlakeId)
    {
        $Binary               = str_pad(decbin($snowFlakeId), 64, '0', STR_PAD_LEFT);
        $Object               = new \stdClass;
        $Object->timestamp    = bindec(substr($Binary, 0, 41)) + self::$twepoch;
        $Object->dataCenterID = bindec(substr($Binary, 42, 5));
        $Object->workerID     = bindec(substr($Binary, 47, 5));
        $Object->sequence     = bindec(substr($Binary, -12));

        return $Object;
    }

    public static function sequence(int $currentTime)
    {
        $redis = RedisService::getContent();
        $key   = self::prefix.$currentTime;

        $lua = <<<'EOT'
            local num = redis.call('incr',KEYS[1])
            redis.call('expire',KEYS[1],ARGV[1])
            return num
        EOT;

        return $redis->eval($lua, [$key, 10], 1);
    }

    private static function timeGen()
    {
        // return (float)sprintf('%.0f', microtime(true) * 1000);
        return time();
    }
}
