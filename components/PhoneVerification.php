<?php

namespace app\components;

use dix\base\component\DXUtil;
use dix\base\component\Redis;

class PhoneVerification extends \dix\base\component\PhoneVerification
{
    public static function sendCode($phone, $code)
    {
        // todo 发送短信的代码
    }


}