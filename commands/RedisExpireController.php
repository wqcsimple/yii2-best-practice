<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/14/17
 * Time: 12:11 PM
 */
namespace app\commands;

use app\components\DXUtil;
use dix\base\component\Redis;
use yii\console\Controller;

class RedisExpireController extends Controller
{
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
    public function actionRedisSubEvent()
    {
        $redis = Redis::clientWithNoPrefix();
        
        $user_func = function ($pubsub, $message)
        {
            consoleLog(DXUtil::jsonEncode($message));
            
            switch ($message->kind) {
                case 'subscribe':
                    echo "Subscribed to {$message->channel}\n";

                    break;

                case 'pmessage':
                    $payload = $message->payload;
                    echo "$payload \n";

                    break;
            }
        };
        
        
        $redis->pubSubLoop(['psubscribe' => '__key*__:*'], $user_func);
    }
}