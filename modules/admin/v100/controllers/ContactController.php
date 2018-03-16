<?php

namespace app\modules\admin\v100\controllers;

use app\modules\admin\v100\services\ContactService;

class ContactController extends BaseApiController
{

    public function actionSave()
    {
        $contact_id = isset($this->params['contact_id']) ? intval($this->params['contact_id']) : 0 ;
        $content = isset($this->params['content']) ? strval($this->params['content']) : null ;
        $name = isset($this->params['name']) ? strval($this->params['name']) : null ;
        $phone = isset($this->params['phone']) ? strval($this->params['phone']) : null ;
        $email = isset($this->params['email']) ? strval($this->params['email']) : null ;

        $_data = null;
        $_data['contact_id'] = ContactService::saveContact($contact_id, $content, $name, $phone, $email);

        $this->finishSuccess($_data);
    }

    public function actionList()
    {
        $page = isset($this->params['page']) ? intval($this->params['page']) : 1 ;
        $begin_date = isset($this->params['begin_date']) ? strval($this->params['begin_date']) : null ;
        $end_date = isset($this->params['end_date']) ? strval($this->params['end_date']) : null ;
        $name = isset($this->params['name']) ? strval($this->params['name']) : null ;

        $_data = null;
        $_data = ContactService::getContactList($this->user_id, $page, $begin_date, $end_date, $name);

        $this->finishSuccess($_data);
    }

    public function actionRemove()
    {
        $this->checkParams(['contact_id']);

        $contact_id = intval($this->params['contact_id']);

        $_data = null;
        ContactService::removeContact($this->user_id, $contact_id);

        $this->finishSuccess($_data);
    }

    public function actionDetail()
    {
        $this->checkParams(['contact_id']);

        $contact_id = intval($this->params['contact_id']);

        $_data = null;
        $_data['contact'] = ContactService::getContactDetail($this->user_id, $contact_id);

        $this->finishSuccess($_data);
    }

}