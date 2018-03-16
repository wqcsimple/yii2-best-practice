<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/30/17
 * Time: 10:13 AM
 */
namespace app\commands;

use yii\console\Controller;

class SocketController extends Controller
{
    public function actionRun()
    {
        $websocket = new \Hoa\Websocket\Server(
            new \Hoa\Socket\Server('ws://127.0.0.1:8889')
        );

        $websocket->on('open', function (\Hoa\Event\Bucket $bucket) {
            echo 'new connection', "\n";

            return;
        });
        $websocket->on('message', function (\Hoa\Event\Bucket $bucket) {
            $data = $bucket->getData();
            echo '> message ', $data['message'], "\n";
            $bucket->getSource()->send($data['message']);
            echo '< echo', "\n";

            return;
        });
        $websocket->on('close', function (\Hoa\Event\Bucket $bucket) {
            echo 'connection closed', "\n";

            return;
        });
        $websocket->run();
    }
}