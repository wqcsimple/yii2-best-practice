<?php

namespace app\modules\blog\v100\controllers;

use app\modules\blog\v100\services\CommonService;

class CommonController extends BaseApiController
{

    public function actionPhoneVerificationCodeSend()
    {
        $this->checkParams(['phone']);

        $phone = strval($this->params['phone']);

        $_data = null;
        CommonService::sendPhoneVCode($phone);

        $this->finishSuccess($_data);
    }

}