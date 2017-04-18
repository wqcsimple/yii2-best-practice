<?php

namespace app\controllers;

use app\components\Debug;
use app\components\DXUtil;
use app\models\User;
use app\modules\client\v101\data\Api;
use app\modules\client\v101\services\TaskService;
use app\services\WebSocketService;
use dix\base\component\Redis;
use GuzzleHttp\Client;
use Yii;
use yii\base\UserException;
use yii\helpers\Html;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionClient($v = 100)
    {
        $v = intval($v);

        $url = "/client/$v/";
        $login_url = $url . 'user/login';
        $action_list = null;

        if ($v == 100) {
            $action_list = \app\modules\client\v100\data\Api::ActionList();
        }

        if (!$action_list) {
            throw new UserException('错误');
        }

        $type_list = [];
        foreach ($action_list as $item) {
            $type = $item['type'];
            if (!in_array($type, $type_list)) {
                $type_list[] = $type;
            }
        }

        $cookie_key_prefix = 'client';

        return $this->render('/test/api', [
            'cookie_key_prefix' => $cookie_key_prefix,
            'url' => $url,
            'login_url' => $login_url,
            'action_list' => $action_list,
            'type_list' => $type_list
        ]);
    }

    public function actionAdmin($v = 100)
    {
        $v = intval($v);

        $url = "/admin/$v/";
        $login_url = $url . 'admin/login';
        $action_list = null;

        if ($v == 100) {
            $action_list = \app\modules\admin\v100\data\Api::ActionList();
        }

        if (!$action_list) {
            throw new UserException('错误');
        }

        $type_list = [];
        foreach ($action_list as $item) {
            $type = $item['type'];
            if (!in_array($type, $type_list)) {
                $type_list[] = $type;
            }
        }

        $cookie_key_prefix = 'admin';

        return $this->render('/test/api', [
            'cookie_key_prefix' => $cookie_key_prefix,
            'url' => $url,
            'login_url' => $login_url,
            'action_list' => $action_list,
            'type_list' => $type_list
        ]);
    }

    public function actionDebugList()
    {
        Debug::getDebugList();
    }

    public function actionTest()
    {

        $pc = "uuid280b0b20277d156a4da1b0441d1291568261092c";
        $uid = "70852181";
        $url = "http://walk.ledongli.cn/rest/dailystats/upload/v3?uid=70852181&pc=uuid280b0b20277d156a4da1b0441d1291568261092c&v=7.1%20ios&vc=712%20ios";
        $step = "666";
        $stats = [
            [
                'calories' => 0,
                'duration' => 0,
                'key' => "b1da3081d362f8c2c862c7e4435ba905",
                'steps' => $step,
                'distance' => '20.2',
                'date' => time(),

            ]
        ];
        $request_data = [
            'pc' => $pc,
            'uid' => $uid,
            'stats' => DXUtil::jsonEncode($stats)
        ];
        $string = http_build_query($request_data);

//        $string = "pc=uuid280b0b20277d156a4da1b0441d1291568261092c&stats=%5B%7B%22calories%22%3A0.16329327684479999%2C%22duration%22%3A0%2C%22key%22%3A%22b1da3081d362f8c2c862c7e4435ba905%22%2C%22steps%22%3A" . $step . "%2C%22distance%22%3A6.2303999999999995%2C%22date%22%3A1492099200%7D%5D&uid=70852181";
//        dump(urldecode($string)); die;
        
        $client = new Client();
        $res = $client->post($url, [
            'form_params' => [
                'data' => $string
            ]
        ]);
        $response = @json_decode('' . $res->getBody(), true);
        dump($response);
        
//        date_default_timezone_set('Asia/Shanghai');
//        $uid = '70852181';//乐动力的用户id
//        $steps = '1234';//想刷的步数
//        $url = 'http://pl.api.ledongli.cn/xq/io.ashx?&action=profile&cmd=updatedaily&uid='.$uid.'&v=5.5%20ios&vc=551%20ios';
//        $post = 'list=%5B%7B%22pm2d5%22%3A0%2C%22report%22%3A%22%5B%7B%5C%22activity%5C%22%3A%5C%22walking%5C%22%2C%5C%22calories%5C%22%3A437.14414200191618%2C%5C%22steps%5C%22%3A11111%2C%5C%22distance%5C%22%3A7519.0167199999987%2C%5C%22duration%5C%22%3A7140%7D%5D%22%2C%22distance%22%3A7519.0167199999987%2C%22steps%22%3A'.$steps.'%2C%22location%22%3A%22%E6%9D%AD%E5%B7%9E%E5%B8%82%22%2C%22date%22%3A'.mktime(0, 0, 0, date('n'), date('j'), date('Y')).'%2C%22calories%22%3A503.07246579211363%2C%22duration%22%3A6060%2C%22lon%22%3A120.2145220549457%2C%22activeValue%22%3A138208%2C%22lat%22%3A30.209404890080783%7D%5D&pc=fef5836f1127975bc9d19f8a24bb2cb3e46b6530';
//        $response = curl("POST", $url, $post);
//        dump($response);
//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//        curl_exec($ch);
//        curl_close($ch);
    }

}