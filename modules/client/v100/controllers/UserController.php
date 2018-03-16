<?php

namespace app\modules\client\v100\controllers;

use app\modules\client\v100\services\UserService;

class UserController extends BaseApiController
{

    public function actionLogin()
    {
        $this->checkParams(['username', 'password']);

        $username = strval($this->params['username']);
        $password = strval($this->params['password']);

        $_data = null;
        $_data = UserService::login($username, $password);

        $this->finishSuccess($_data);
    }

}