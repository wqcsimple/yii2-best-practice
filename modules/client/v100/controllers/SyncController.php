<?php

namespace app\modules\client\v100\controllers;

use app\modules\client\v100\services\SyncService;

class SyncController extends BaseApiController
{

    public function actionRoleSync()
    {
        $this->checkParams(['role', 'data']);

        $role = strval($this->params['role']);
        $data = strval($this->params['data']);

        $_data = null;
        SyncService::roleSync($role, $data);

        $this->finishSuccess($_data);
    }

}