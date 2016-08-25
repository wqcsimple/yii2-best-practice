<?php

namespace smartwork\user\api\controller;

use dix\base\controller\BaseController;
use dix\base\component\RedisCache;
use dix\base\component\RedisSession;
use smartwork\user\api\component\SmartworkUserApi;
use smartwork\user\api\data\Api;
use smartwork\user\api\service\ApiService;
use smartwork\user\api\service\UserService;
use yii\base\UserException;

class TestController extends BaseController
{
    public function actionRedis()
    {
    }

    public function actionAuth()
    {
        dump(SmartworkUserApi::authAuth('4PBlyF5SB', 99));
        dump(SmartworkUserApi::authOACheck('4PBlyF5SB', 99));
    }

    public function actionMember()
    {
        dump(SmartworkUserApi::orgMemberListWithBaseAuth(99, '1,2,3,4'));
    }
    
    public function actionUserStat()
    {
        dump(ApiService::getUserStat(date('Y-m-d', time())));
    }
}