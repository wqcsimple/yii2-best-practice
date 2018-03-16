<?php

namespace app\modules\admin\v100\controllers;

use app\modules\admin\v100\services\AdminService;

class AdminController extends BaseApiController
{

    public function actionLogin()
    {
        $this->checkParams(['username', 'password']);

        $username = strval($this->params['username']);
        $password = strval($this->params['password']);

        $_data = null;
        $_data = AdminService::login($this->user_id, $username, $password);

        $this->finishSuccess($_data);
    }

    public function actionLogout()
    {
        $_data = null;
        AdminService::logout($this->user_id);

        $this->finishSuccess($_data);
    }

    public function actionUpdatePassword()
    {
        $this->checkParams(['old_password', 'password']);

        $old_password = strval($this->params['old_password']);
        $password = strval($this->params['password']);

        $_data = null;
        AdminService::updatePassword($this->user_id, $old_password, $password);

        $this->finishSuccess($_data);
    }

    public function actionAdd()
    {
        $this->checkParams(['username', 'name', 'password']);

        $username = strval($this->params['username']);
        $name = strval($this->params['name']);
        $password = strval($this->params['password']);

        $email = isset($this->params['email']) ? strval($this->params['email']) : null ;
        $gender = isset($this->params['gender']) ? intval($this->params['gender']) : null ;

        $_data = null;
        $_data['admin'] = AdminService::addAdmin($this->user_id, $username, $name, $password, $email, $gender);

        $this->finishSuccess($_data);
    }

    public function actionList()
    {
        $name = isset($this->params['name']) ? strval($this->params['name']) : null ;

        $_data = null;
        $_data = AdminService::getAdminList($this->user_id, $name);

        $this->finishSuccess($_data);
    }

    public function actionInfo()
    {
        $_data = null;
        $_data['info'] = AdminService::getAdminInfo($this->user_id);

        $this->finishSuccess($_data);
    }

    public function actionDetail()
    {
        $admin_id = isset($this->params['admin_id']) ? intval($this->params['admin_id']) : 0 ;

        $_data = null;
        $_data['detail'] = AdminService::getAdminDetail($this->user_id, $admin_id);

        $this->finishSuccess($_data);
    }

    public function actionInfoUpdate()
    {
        $admin_id = isset($this->params['admin_id']) ? intval($this->params['admin_id']) : 0 ;
        $name = isset($this->params['name']) ? strval($this->params['name']) : null ;
        $email = isset($this->params['email']) ? strval($this->params['email']) : null ;
        $gender = isset($this->params['gender']) ? intval($this->params['gender']) : null ;

        $_data = null;
        AdminService::updateAdminInfo($this->user_id, $admin_id, $name, $email, $gender);

        $this->finishSuccess($_data);
    }

    public function actionPasswordUpdate()
    {
        $admin_id = isset($this->params['admin_id']) ? intval($this->params['admin_id']) : 0 ;
        $password = isset($this->params['password']) ? strval($this->params['password']) : null ;

        $_data = null;
        AdminService::updateAdminPassword($this->user_id, $admin_id, $password);

        $this->finishSuccess($_data);
    }

    public function actionDelete()
    {
        $admin_id = isset($this->params['admin_id']) ? intval($this->params['admin_id']) : 0 ;

        $_data = null;
        AdminService::deleteAdmin($this->user_id, $admin_id);

        $this->finishSuccess($_data);
    }

}