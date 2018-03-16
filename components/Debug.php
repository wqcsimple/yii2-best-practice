<?php
/**
 * usage 
 * Debug::debugLog('wx-login-redirect-url', ['url' => $authUrl]);
 * Created by PhpStorm.
 * User: whis
 * Date: 3/10/17
 * Time: 1:34 PM
 */
namespace app\components;

use dix\base\component\Redis;

class Debug
{
    const KEY_DEBUG_LIST = 'debug-list';
    
    public static function log($key, $data)
    {
        $now = time();
        $key = "$key.$now";
        $redis = Redis::client();

        $data['REQUEST'] = $_REQUEST;

        $redis->pipeline()
            ->HSET($key, 'data', DXUtil::jsonEncode($data))
            ->zadd(self::KEY_DEBUG_LIST, [$key => $now])
            ->execute();
    }

    public static function getDebugList($size = 100)
    {
        $redis = Redis::client();
        $records = $redis->ZREVRANGE(self::KEY_DEBUG_LIST, 0, $size, 'WITHSCORES');
        if (is_array($records))
        {
            foreach ($records as $key => $score)
            {
                $debug = $redis->HGETALL($key);
                dump([
                    'key' => $key,
                    'time' => DXUtil::timeFormat($score),
                    'item' => $debug
                ]);
            }
        }
    }
}