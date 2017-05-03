<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/3/17
 * Time: 2:27 PM
 */
namespace app\services;

use app\components\DXUtil;
use app\models\Item;
use dix\base\exception\ServiceErrorSaveException;

class OrderService
{
    public static function makeSn()
    {
        $sn = DXUtil::generateValidUid(function (){
            $time = date('ymd', time());
            $sn = '8' . $time . DXUtil::generateRandomNumberString(9);
            $sn = substr($sn, 0, 12);
            
            return $sn;
        }, function ($sn) {
            return Item::find()->where(['sn' => $sn])->exists();
        }, function ($sn) {
           return $sn; 
        });

        if (!$sn)
        {
            throw new ServiceErrorSaveException('生成单号失败');
        }

        return $sn;
    }
    
    public static function getOrderIdList()
    {
        $order_id_list = DXUtil::getSingleColumnValueListFromQuery(Item::find()->where('weight >= 0'));
        $order_sn_list = DXUtil::getSingleColumnValueListFromQuery(Item::find()->where('weight >= 0'), 'sn');
     
        return [$order_id_list, $order_sn_list];
    }
}