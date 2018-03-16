<?php

namespace app\modules\admin\v100\controllers;

use app\modules\admin\v100\services\CommonService;

class CommonController extends BaseApiController
{

    public function actionPhoneVerificationCodeSend()
    {
        $this->checkParams(['phone']);

        $phone = intval($this->params['phone']);

        $_data = null;
        CommonService::sendPhoneVCode($phone);

        $this->finishSuccess($_data);
    }

    public function actionPhoneVerificationCodeCheck()
    {
        $this->checkParams(['phone', 'code']);

        $phone = intval($this->params['phone']);
        $code = intval($this->params['code']);

        $_data = null;
        CommonService::checkPhoneVCode($phone, $code);

        $this->finishSuccess($_data);
    }

}