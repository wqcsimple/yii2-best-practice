<?php

namespace app\modules\client\v100\controllers;

use app\modules\client\v100\services\DataService;

class DataController extends BaseApiController
{

    public function actionSave()
    {
        $this->checkParams(['name', 'price']);

        $name = strval($this->params['name']);
        $price = intval($this->params['price']);

        $data_id = isset($this->params['data_id']) ? intval($this->params['data_id']) : 0 ;
        $img = isset($this->params['img']) ? strval($this->params['img']) : null ;
        $desc = isset($this->params['desc']) ? strval($this->params['desc']) : null ;

        $_data = null;
        $_data['data_id'] = DataService::saveData($data_id, $name, $price, $img, $desc);

        $this->finishSuccess($_data);
    }

    public function actionList()
    {
        $page = isset($this->params['page']) ? intval($this->params['page']) : 1 ;
        $name = isset($this->params['name']) ? strval($this->params['name']) : null ;
        $min_price = isset($this->params['min_price']) ? intval($this->params['min_price']) : null ;
        $max_price = isset($this->params['max_price']) ? intval($this->params['max_price']) : null ;

        $_data = null;
        $_data = DataService::dataList($page, $name, $min_price, $max_price);

        $this->finishSuccess($_data);
    }

    public function actionDetail()
    {
        $this->checkParams(['data_id']);

        $data_id = intval($this->params['data_id']);

        $_data = null;
        $_data['detail'] = DataService::detail($data_id);

        $this->finishSuccess($_data);
    }

    public function actionDelete()
    {
        $this->checkParams(['data_id']);

        $data_id = intval($this->params['data_id']);

        $_data = null;
        $_data = DataService::deleteData($data_id);

        $this->finishSuccess($_data);
    }

    public function actionDeleteItemPrice()
    {
        $this->checkParams(['item_price_id']);

        $item_price_id = intval($this->params['item_price_id']);

        $_data = null;
        $_data = DataService::deleteItemPrice($item_price_id);

        $this->finishSuccess($_data);
    }

    public function actionPriceList()
    {
        $this->checkParams(['item_id']);

        $item_id = intval($this->params['item_id']);

        $_data = null;
        $_data = DataService::getItemPriceList($item_id);

        $this->finishSuccess($_data);
    }

    public function actionSaveItemPrice()
    {
        $this->checkParams(['item_id']);

        $item_id = intval($this->params['item_id']);

        $price = isset($this->params['price']) ? intval($this->params['price']) : null ;
        $img = isset($this->params['img']) ? strval($this->params['img']) : null ;
        $desc = isset($this->params['desc']) ? strval($this->params['desc']) : null ;

        $_data = null;
        $_data = DataService::saveItemPrice($item_id, $price, $img, $desc);

        $this->finishSuccess($_data);
    }

}