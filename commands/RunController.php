<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\DXConst;
use app\components\DXKey;
use app\components\DXUtil;
use app\components\PushMessage;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RunController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }
    
    public function actionRedisPushMessage()
    {
        /**
         * @var \Predis\Client $redis
         */

        $redis = DXUtil::redisPubSubClient();

        $user_func = function($pubsub, $message){

            $redis = DXUtil::redisClient();

            switch ($message->kind) {
                case 'subscribe':
                    consoleLog("subscribed to {$message->channel}");
                    break;

                case 'message':
                    consoleLog('received message: ' . json_encode($message));
                    $count = 0;
                    while (true)
                    {
                        $count++;
                        consoleLog($count);
                        if ($count > 10)
                        {
                            consoleLog('loop end');
                            break;
                        }

                        $payload = $redis->lpop(DXKey::getKeyOfPushMessageListWorking());
                        consoleLog('pop: ' . $payload);
                        if (!$payload)
                        {
                            consoleLog('lpop null break');
                            break;
                        }

                        $payload = @json_decode($payload, true);
                        if (isset($payload['type']) && isset($payload['list']) && isset($payload['data']))
                        {
                            $type = intval($payload['type']);
                            $list = $payload['list'];
                            $data = $payload['data'];
                            if (is_array($list) && count($list) > 0)
                            {
                                $data = json_encode($data);
                                consoleLog('push: ' . json_encode($data));
                                try
                                {
                                    if ($type == 1)
                                    {
                                        PushMessage::pushMessageByRabbitMQ($list, $data);
                                    }
                                    elseif ($type == 2)
                                    {
                                        PushMessage::pushMessageByXG($list, $data);
                                    }
                                    elseif ($type == 3)
                                    {
                                        PushMessage::pushMessageByJPush($list, $data);
                                    }
                                }
                                catch (\Exception $e)
                                {
                                    consoleLog('exception, push message to fail list');
                                    $redis->lpush(DXKey::getKeyOfPushMessageListFail(), json_encode($payload));
                                }

                            }
                        }
                    }

                    break;
            }
        };

        $redis->pubSubLoop(['subscribe' => DXKey::getKeyOfChannelPushMessage()], $user_func);

    }
}
