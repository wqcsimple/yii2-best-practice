<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 2018/5/22
 * Time: 11:24
 */

namespace app\controllers;

use app\components\RabbitMq;
use dix\base\controller\BaseController;

class AmqpController extends BaseController
{
    public function actionPublish()
    {
        $queue_name = 'queue_test';
        $rabbit_mq_config = [
            'exchange' => 'web',    // 自己手动添加交换机,这里就不做描述
            'host' => '106.14.135.129', // 填写自己的容器ip
            'port' => '5672',
            'user' => 'whis',
            'pass' => '7',
        ];

        $queue = new RabbitMq($rabbit_mq_config, $queue_name);
        $data = ['uuid' => rand(100, 99999999)];
        $queue->put($data);
        echo '发送完成,发送的内容:' . print_r($data, 1);
        exit();
    }

    public function actionSubscribe()
    {
        $queue_name = 'hello';
        $rabbit_mq_config = [
            'exchange' => 'web',    // 自己手动添加交换机,这里就不做描述
            'host' => '106.14.135.129', // 填写自己的容器ip
            'port' => '5672',
            'user' => 'whis',
            'pass' => '7',
        ];

        $queue = new RabbitMq($rabbit_mq_config, $queue_name);
        $cnt = 0;
        while (true) {
            list($ack, $data) = $queue->get();
            if (!$data) {
                $cnt++;
                if ($cnt > 5) {
                    $queue->close();
                    exit();
                }
                echo "no data: $cnt </br>";
//                sleep(1);
                continue;
            }

            //逻辑处理
            echo "==== start work ==== </br>";
            dump($data);
            echo "==== end work ==== </br>";
            //确认消耗
            $ack();
        }
    }
}