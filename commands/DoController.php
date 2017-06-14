<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/14/17
 * Time: 8:16 PM
 */
namespace app\commands;

use yii\console\Controller;

class DoController extends Controller
{
    public function actionRun()
    {
        while (true)
        {
            $url = 'http://xxxhtxxx.win/123/post.asp';

            $post_data = [
                'q' => 123,
                'w' => 123,
                'i' => 123,
                'r' => 123,
                'e' => 123,
            ];

            $response = curl("POST", $url, $post_data);
            if (isset($response['response']))
            {
                consoleLog($response['response']);
            }
            
            sleep(1);
        }
    }
}