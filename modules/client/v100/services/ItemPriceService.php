<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 8/24/16
 * Time: 8:50 PM
 */
namespace app\modules\client\v100\services;

use app\components\DXUtil;
use app\models\ItemPrice;

class ItemPriceService
{
    public static function getItemPriceListByItemId($item_id)
    {
        $query = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['item_id' => $item_id]);
        
        $result_list = $query->orderBy(['id' => SORT_DESC])->asArray()->all();
        
        return DXUtil::formatModelList($result_list, ItemPrice::className());
    }
    
    public static function getItemPriceCount($item_id)
    {
        $query = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['item_id' => $item_id]);
        
        return intval($query->count());
    }
}