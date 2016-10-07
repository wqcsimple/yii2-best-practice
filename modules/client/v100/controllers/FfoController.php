<?php

namespace app\modules\client\v100\controllers;

use app\modules\client\v100\services\FfoService;

class FfoController extends BaseApiController
{

    public function actionRoleList()
    {
        $page = isset($this->params['page']) ? intval($this->params['page']) : 1 ;
        $type = isset($this->params['type']) ? intval($this->params['type']) : null ;
        $min_price = isset($this->params['min_price']) ? intval($this->params['min_price']) : null ;
        $max_price = isset($this->params['max_price']) ? intval($this->params['max_price']) : null ;

        $_data = null;
        $_data = FfoService::getRoleList($page, $type, $min_price, $max_price);

        $this->finishSuccess($_data);
    }

    public function actionRoleSave()
    {
        $this->checkParams(['item_id', 'name', 'type', 'avatar', 'add_time']);

        $item_id = strval($this->params['item_id']);
        $name = strval($this->params['name']);
        $type = intval($this->params['type']);
        $avatar = strval($this->params['avatar']);
        $add_time = strval($this->params['add_time']);

        $price = isset($this->params['price']) ? intval($this->params['price']) : 0 ;
        $level = isset($this->params['level']) ? intval($this->params['level']) : 0 ;
        $comment = isset($this->params['comment']) ? strval($this->params['comment']) : null ;

        $_data = null;
        $_data = FfoService::saveRole($item_id, $name, $type, $avatar, $price, $level, $add_time, $comment);

        $this->finishSuccess($_data);
    }

    public function actionRoleDelete()
    {
        $this->checkParams(['id']);

        $id = intval($this->params['id']);

        $_data = null;
        FfoService::deleteRole($id);

        $this->finishSuccess($_data);
    }

    public function actionRoleDetail()
    {
        $this->checkParams(['id']);

        $id = intval($this->params['id']);

        $_data = null;
        $_data['role'] = FfoService::getRoleDetail($id);

        $this->finishSuccess($_data);
    }

    public function actionRoleImgSave()
    {
        $this->checkParams(['id', 'img']);

        $id = intval($this->params['id']);
        $img = strval($this->params['img']);

        $_data = null;
        FfoService::saveRoleImg($id, $img);

        $this->finishSuccess($_data);
    }

    public function actionRoleDataSave()
    {
        $this->checkParams(['id', 'data']);

        $id = intval($this->params['id']);
        $data = strval($this->params['data']);

        $_data = null;
        FfoService::saveRoleData($id, $data);

        $this->finishSuccess($_data);
    }

}