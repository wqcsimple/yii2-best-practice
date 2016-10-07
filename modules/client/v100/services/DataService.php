<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 8/24/16
 * Time: 7:26 PM
 */
namespace app\modules\client\v100\services;

use app\components\DXConst;
use app\components\DXUtil;
use app\models\Item;
use app\models\ItemPrice;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorSaveException;

class DataService
{
    public static function saveData($data_id, $name, $price, $img, $desc)
    {
        $item = Item::findById($data_id);
        if (!$item)
        {
            $item = new Item();
            $item->weight = DXConst::WEIGHT_NORMAL;
        }
        
        $item->name = $name;
        $item = DXUtil::doTransaction(function () use($item, $price, $img, $desc) {
            if (!$item->save())
            {
                throw new ServiceErrorSaveException('save error', ['errors' => $item->errors]);
            }
            
            list($save_ok, $item_price) = ItemPrice::saveItemPrice($item->id, $price, $img, $desc);
            if (!$save_ok)
            {
                throw new ServiceErrorSaveException('save error', ['errors' => $item_price->errors]);
            }
            
            $item->updateMinPrice();
            $item->updateMaxPrice();
            $item->updateAvePrice();
            
            return $item;
        });
        
        return $item->id;
    }
    
    public static function dataList($page, $name, $min_price, $max_price)
    {
        $page = intval($page);
        $page = $page < 1 ? 1 : intval($page);
        $size = 20;
        $offset = ($page - 1) * $size;
        
        $query = Item::find()->where(" weight >= 0 ");
        
        if ($name)
        {
            $query = $query->andWhere(['like', 'name', $name]);
        }
        if ($min_price)
        {
            $query = $query->andWhere([">=", 'price', $min_price]);
        }
        if ($max_price)
        {
            $query = $query->andWhere(["<=", "price", $max_price]);
        }
        
        $count = intval($query->count());
        
        $result_list = $query->orderBy(['id' => SORT_DESC])->offset($offset)->limit($size)->all();
        $data_list = DXUtil::formatModelList($result_list, Item::className(), 'processForAdmin');
        
        return [
            "count" => $count,
            "list" => $data_list
        ];
    }
    
    public static function detail($data_id)
    {
        return Item::getRawById($data_id);
    }
    
    public static function deleteData($data_id)
    {
        $item = Item::findById($data_id);
        if (!$item)
        {
            throw new ServiceErrorNotExistsException();
        }
        
        $item->weight = DXConst::WEIGHT_DELETED;
        if (!$item->save())
        {
            throw new ServiceErrorSaveException();
        }
    }
    
    public static function deleteItemPrice($item_price_id)
    {
        $item_price = ItemPrice::findById($item_price_id);
        if (!$item_price)
        {
            throw new ServiceErrorNotExistsException();
        }
        
        $item_price->weight = DXConst::WEIGHT_DELETED;
        if (!$item_price->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $item_price->errors]);
        }
        
        $item = Item::findById($item_price->item_id);
        $item->updateMinPrice();
        $item->updateMaxPrice();
        $item->updateAvePrice();
    }
    
    public static function getItemPriceList($item_id)
    {
        $query = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['item_id' => $item_id]);
        
        $result_list = $query->orderBy(['id' => SORT_DESC])->asArray()->all();
        
        $list = DXUtil::formatModelList($result_list, ItemPrice::className());
        
        return [
            "list" => $list
        ];
    }
    
    public static function saveItemPrice($item_id, $price, $img, $desc)
    {
        $item = Item::findById($item_id);
        if (!$item)
        {
            throw new ServiceErrorNotExistsException();
        }
        
        list($save_ok, $item_price) = ItemPrice::saveItemPrice($item_id, $price, $img, $desc);
        if (!$save_ok)
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $item_price->errors]);
        }
        $item->updateMinPrice();
        $item->updateMaxPrice();
        $item->updateAvePrice();
    }
}