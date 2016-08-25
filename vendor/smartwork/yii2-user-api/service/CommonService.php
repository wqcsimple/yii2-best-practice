<?php

namespace smartwork\user\api\service;


use dix\base\component\PhoneVerification;
use dix\base\exception\ServiceErrorWrongVCodeException;

class CommonService
{
    public static function sendPhoneVCode($phone)
    {
        
    }

    public static function checkPhoneVCode($phone, $code)
    {
        if (!PhoneVerification::validate($phone, $code))
        {
            throw new ServiceErrorWrongVCodeException();
        }
    }

}