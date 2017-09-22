<?php

namespace app\modules\client\v100\controllers;

use app\modules\client\v100\services\RoleService;

class RoleController extends BaseApiController
{

    public function actionSave()
    {
        $this->checkParams(['name']);

        $name = strval($this->params['name']);

        $role_id = isset($this->params['role_id']) ? intval($this->params['role_id']) : 0 ;

        $_data = null;
        RoleService::saveRole($role_id, $name);

        $this->finishSuccess($_data);
    }

    public function actionDelete()
    {
        $this->checkParams(['role_id']);

        $role_id = intval($this->params['role_id']);

        $_data = null;
        RoleService::deleteRole($role_id);

        $this->finishSuccess($_data);
    }

    public function actionRolePriceAdd()
    {
        $this->checkParams(['role_id', 'item_price_id']);

        $role_id = intval($this->params['role_id']);
        $item_price_id = intval($this->params['item_price_id']);

        $_data = null;
        RoleService::addRolePrice($role_id, $item_price_id);

        $this->finishSuccess($_data);
    }

    public function actionRolePriceDelete()
    {
        $this->checkParams(['role_price_id']);

        $role_price_id = intval($this->params['role_price_id']);

        $_data = null;
        RoleService::deleteRolePrice($role_price_id);

        $this->finishSuccess($_data);
    }

}