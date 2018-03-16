<?php

namespace app\controllers;

use app\components\DXKey;
use app\models\User;
use app\modules\client\v100\services\ClientApiService;
use dix\base\component\Redis;
use yii\web\Controller;

class DataController extends Controller
{
    public function actionUserActionRank($date = null)
    {
        if (!$date)
        {
            $date = date('Y-m-d', time());
        }

        echo '<div style=\'font-size: 14px; color: #555; font-family:"Helvetica Neue", Helvetica, "Nimbus Sans L", Arial, "Liberation Sans", "PingFang SC", "Hiragino Sans GB", "Source Han Sans CN", "Source Han Sans SC", "Microsoft YaHei", "Wenquanyi Micro Hei", "WenQuanYi Zen Hei", "ST Heiti", SimHei, "WenQuanYi Zen Hei Sharp", sans-serif;margin:0;padding:0;z-index:auto;outline:none;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-webkit-appearance:none;-webkit-font-smoothing:antialiased;\'>';

        $rank_list = ClientApiService::getUserStat($date);
        foreach ($rank_list as $user_id => $count)
        {
            $user = User::getUserRawById($user_id);
            $user_name = isset($user['name']) ? $user['name'] : $user_id;
            echo '<div>' . $user_name . ' ' . $count . '</div>';
        }

        echo '</div>';
    }

    public function actionApiStat()
    {
        $redis = Redis::client();
        $key_rank = DXKey::getKeyOfActionTimeRank();
        $records = $redis->ZREVRANGE($key_rank, 0, -1, 'WITHSCORES');
        $stat = [];
        if (is_array($records))
        {
            foreach ($records as $key => $score)
            {
                $action = $redis->HGETALL($key);
                $action['score'] = $score;
                $stat[] = [$key => $action];
            }
        }

        dump($records);

        dump($stat);


    }
}