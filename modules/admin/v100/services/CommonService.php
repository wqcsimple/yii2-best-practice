<?php

namespace app\modules\admin\v100\services;

use dix\base\component\PhoneVerificationCode;
use dix\base\exception\ServiceErrorWrongVCodeException;

class CommonService extends \dix\base\service\CommonService
{
    public static function sendPhoneVCode($phone)
    {
        PhoneVerificationCode::send($phone);
    }

    public static function checkPhoneVCode($phone, $code)
    {
        if (!PhoneVerificationCode::validate($phone, $code))
        {
            throw new ServiceErrorWrongVCodeException();
        }
    }

}