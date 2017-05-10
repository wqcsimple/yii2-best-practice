<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 5/26/15
 * Time: 23:41
 */
namespace app\modules\blog\v100\data;

class Api
{
    const TYPE_COMMON = "common";

    public static function PathGuestCanAccess()
    {
        return [
        ];
    }

    public static function ActionList()
    {
        
        $common_actions = [
              [
                  'type' => self::TYPE_COMMON,
                  'name' => 'common - send-phone',
                  'action' => 'common/phone-verification-code-send',
                  'token' => false,
                  'params' => ['phone | s'],
                  'response' => [
                      "null" => '\app\modules\blog\v100\services\CommonService::sendPhoneVCode($phone)',
                  ]
              ]
        ];


        return array_merge($common_actions);
    }


}