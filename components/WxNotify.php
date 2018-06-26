<?php
namespace app\components;

use GuzzleHttp\Client;

class WxNotify {

    const END_POINT = "http://sc.ftqq.com/SCU6349T23ce526185f836b02508e20b7b80f83e58b95e2a4234a.send";

    public static function send($title, $content = "") {
        $client = new Client();

        $request_data = [
            'text' => $title,
            'desp' => "`${content}`"
        ];
        $res = $client->post(self::END_POINT, [
            'form_params' => $request_data
        ]);

        $response = @json_decode('' . $res->getBody(), true);

        \Yii::info(DXUtil::jsonEncode($response));
    }
}