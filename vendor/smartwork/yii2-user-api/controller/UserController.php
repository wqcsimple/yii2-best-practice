<?php

namespace smartwork\user\api\controller;

use smartwork\user\api\service\UserService;

class UserController extends BaseApiController
{

    public function actionLogin()
    {
        $this->checkParams(['phone', 'password']);

        $phone = intval($this->params['phone']);
        $password = strval($this->params['password']);

        $data = null;
        $data = UserService::login($phone, $password, $this->client, $this->version);

        $this->finishSuccess($data);
    }

    public function actionLoginByWx()
    {
        $this->checkParams(['unionid']);

        $unionid = strval($this->params['unionid']);

        $data = null;
        $data['user'] = UserService::loginByWx($unionid, $this->client, $this->version);
        $data['token'] = UserService::makeToken($this->client, $this->version, $data["user"]["id"]);

        $this->finishSuccess($data);
    }

    public function actionLoginAsAdmin()
    {
        $this->checkParams(['phone', 'password']);

        $phone = intval($this->params['phone']);
        $password = strval($this->params['password']);

        $data = null;
        $data = UserService::loginAsAdmin($phone, $password, $this->client, $this->version);

        $this->finishSuccess($data);
    }

    public function actionLoginAsAdminByWx()
    {
        $this->checkParams(['unionid']);

        $unionid = strval($this->params['unionid']);

        $data = null;
        $data = UserService::loginAsAdminByWx($unionid, $this->client, $this->version);

        $this->finishSuccess($data);
    }

    public function actionRegister()
    {
        $this->checkParams(['phone', 'password', 'code']);

        $phone = intval($this->params['phone']);
        $password = strval($this->params['password']);
        $code = intval($this->params['code']);

        $data = null;
        $data['user'] = UserService::register($phone, $password, $code);

        $this->finishSuccess($data);
    }

    public function actionRegisterByWx()
    {
        $this->checkParams(['unionid']);

        $unionid = strval($this->params['unionid']);

        $data = null;
        $data['user'] = UserService::registerByWx($unionid);
        $data['token'] = UserService::makeToken($this->client, $this->version, $data["user"]["id"]);

        $this->finishSuccess($data);
    }

    public function actionLogout()
    {
        $data = null;
        UserService::logout($this->user_id);

        $this->finishSuccess($data);
    }

    public function actionDetailByPhone()
    {
        $this->checkParams(['phone']);

        $phone = intval($this->params['phone']);

        $data = null;
        $data['user'] = UserService::getUserDetailByPhone($phone);

        $this->finishSuccess($data);
    }

    public function actionDetail()
    {
        $data = null;
        $data['user'] = UserService::getUserDetail($this->user_id);

        $this->finishSuccess($data);
    }

    public function actionPasswordUpdate()
    {
        $this->checkParams(['old_password', 'password']);

        $old_password = strval($this->params['old_password']);
        $password = strval($this->params['password']);

        $data = null;
        UserService::updateUserPassword($this->user_id, $old_password, $password);

        $this->finishSuccess($data);
    }

    public function actionPasswordReset()
    {
        $this->checkParams(['phone', 'password', 'code']);

        $phone = intval($this->params['phone']);
        $password = strval($this->params['password']);
        $code = intval($this->params['code']);

        $data = null;
        UserService::resetPassword($phone, $password, $code);

        $this->finishSuccess($data);
    }

    public function actionAvatarUpdate()
    {
        $this->checkParams(['img']);

        $img = strval($this->params['img']);

        $data = null;
        UserService::updateUserAvatar($this->user_id, $img) ;

        $this->finishSuccess($data);
    }

    public function actionBasicInfoUpdate()
    {
        $name = isset($this->params['name']) ? strval($this->params['name']) : null ;
        $email = isset($this->params['email']) ? strval($this->params['email']) : null ;
        $gender = isset($this->params['gender']) ? intval($this->params['gender']) : -1 ;
        $birthday = isset($this->params['birthday']) ? intval($this->params['birthday']) : 0 ;

        $data = null;
        UserService::updateUserBasicInfo($this->user_id, $name, $email, $gender, $birthday);

        $this->finishSuccess($data);
    }

    public function actionPhoneUpdate()
    {
        $this->checkParams(['phone', 'code']);

        $phone = intval($this->params['phone']);
        $code = intval($this->params['code']);

        $data = null;
        UserService::updateUserPhone($this->user_id, $phone, $code);

        $this->finishSuccess($data);
    }

    public function actionOuterUserInfoUpdate()
    {
        $this->checkParams(['type', 'outer_user_data']);

        $type = intval($this->params['type']);
        $outer_user_data = strval($this->params['outer_user_data']);

        $data = null;
        UserService::updateOuterUserInfo($this->user_id, $type, $outer_user_data);

        $this->finishSuccess($data);
    }

    public function actionOuterUserList()
    {
        $data = null;
        $data['outer_user_list'] = UserService::getOuterUserList($this->user_id);

        $this->finishSuccess($data);
    }

    public function actionOuterUserBind()
    {
        $this->checkParams(['type', 'outer_user_id', 'outer_user_data']);

        $type = intval($this->params['type']);
        $outer_user_id = strval($this->params['outer_user_id']);
        $outer_user_data = strval($this->params['outer_user_data']);

        $data = null;
        UserService::bindOuterUser($this->user_id, $type, $outer_user_id, $outer_user_data);

        $this->finishSuccess($data);
    }

    public function actionOuterUserUnbind()
    {
        $this->checkParams(['type']);

        $type = intval($this->params['type']);

        $data = null;
        UserService::unbindOuterUser($this->user_id, $type);

        $this->finishSuccess($data);
    }

}