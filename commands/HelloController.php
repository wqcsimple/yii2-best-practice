<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    public function actionRedis()
    {
        /**
         * @var \Predis\Client $redis
         */

        $redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'redis',
            'port'   => 6379,
            'password' => '5WSZZ86WvQIDnkyTPJuHAZUio1OzcEb',
            'read_write_timeout' => 0
        ]);

        $user_func = function($pubsub, $message){
            switch ($message->kind) {
                case 'subscribe':
                    echo "Subscribed to {$message->channel}\n";

                    break;

                case 'message':
                    $payload = $message->payload;
                    echo "$payload \n";

                    break;
            }
        };

        $redis->pubSubLoop(['subscribe' => 'test'], $user_func);

    }
}
