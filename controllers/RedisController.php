<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/14/17
 * Time: 12:00 PM
 */
namespace app\controllers;

use dix\base\component\Redis;
use dix\base\controller\BaseController;
use Predis\PubSub\DispatcherLoop;
use yii\helpers\Json;

class RedisController extends BaseController
{
    public function actionSet()
    {
        $redis = Redis::client();
        dump($redis->getOptions());
        
        $redis->setex('test1',3,'hello world - 1');
        $redis->setex('test2',3,'hello world - 2');
    }
    
    public function actionSub()
    {
        $redis = Redis::client();
        $pubsub = $redis->pubSubLoop();
        $dispatcher = new DispatcherLoop($pubsub);

        
        $dispatcher->attachCallback('pmessage', function ($pubsub, $message) {
            dump($message);
        });

        $dispatcher->attachCallback('__key*__:*', function ($payload) use ($dispatcher) {
            dump($payload);
//            if ($payload === 'terminate_dispatcher') {
//                $dispatcher->stop();
//            }
        });


        $dispatcher->run();
        
        $version = redis_version($redis->info());
        echo "Goodbye from Redis $version!", PHP_EOL;
        
    }
    
    public function actionTest()
    {
        $redis = Redis::client();
        
        $data = [
            'value' => '1',
            'time' => "2017",
            'date' => "0501"
        ];
        $redis->hset('website', 'whis', Json::encode($data));
        
    }
}

