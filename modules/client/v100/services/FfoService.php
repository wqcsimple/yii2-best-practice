<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 10/7/16
 * Time: 1:07 PM
 */
namespace app\modules\client\v100\services;

use app\components\DXConst;
use app\components\DXUtil;
use app\models\FfoData;
use dix\base\exception\ServiceErrorNotExistsException;
use dix\base\exception\ServiceErrorSaveException;

class FfoService 
{
    public static function getRoleList($page, $type, $min_price, $max_price)
    {
        $page = intval($page);
        $page = $page < 1 ? 1 : intval($page);
        $size = 20;
        $offset = ($page - 1) * $size;
        
        $query = FfoData::find()->where(" weight >= 0 ");
        if ($type)
        {
            $query = $query->andWhere(['type' => $type]);
        }
        if ($min_price)
        {
            $query = $query->andWhere(['>=', 'price', $min_price]);
        }
        if ($max_price)
        {
            $query = $query->andWhere(['<=', 'price', $max_price]);
        }
        
        $count = intval($query->count());
        $db_list = $query->orderBy(['id' => SORT_DESC, 'create_time' => SORT_DESC])->offset($offset)->limit($size)->asArray()->all();
        $list = DXUtil::formatModelList($db_list, FfoData::className());
        
        return [
            "count" => $count,
            'list' => $list
        ];
    }
    
    public static function saveRole($item_id, $name, $type, $avatar, $price, $level, $add_time, $comment)
    {
        $db_ffo = new FfoData();
        $db_ffo->weight = DXConst::WEIGHT_NORMAL;
        
        $db_ffo->item_id = $item_id;
        $db_ffo->name = $name;
        $db_ffo->type = $type;
        $db_ffo->avatar = $avatar;
        $db_ffo->price = $price;
        $db_ffo->level = $level;
        $db_ffo->add_time = $add_time;
        $db_ffo->comment = $comment;
        
        if (!$db_ffo->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $db_ffo->errors]);
        }
        
        return ["id" => $db_ffo->id];
    }
    
    public static function saveRoleImg($id, $img)
    {
        $db_ffo = FfoData::findById($id);
        if (!$db_ffo) 
        {
            throw new ServiceErrorNotExistsException();
        }
        $save_img_list = $db_ffo->images ? $db_ffo->images . "," . $img : $img;
            
        $db_ffo->images = $save_img_list;
        
        if (!$db_ffo->save())
        {
            throw new ServiceErrorSaveException();
        }
    }
    
    public static function saveRoleData($id, $data)
    {
        $db_ffo = FfoData::findById($id);
        if (!$db_ffo)
        {
            throw new ServiceErrorNotExistsException();
        }

        $db_ffo->data = $data;

        if (!$db_ffo->save())
        {
            throw new ServiceErrorSaveException();
        }
    }
    
    public static function deleteRole($id)
    {
        $db_ffo = FfoData::findById($id);
        if (!$db_ffo)
        {
            throw new ServiceErrorNotExistsException();
        }

        $db_ffo->weight = DXConst::WEIGHT_DELETED;

        if (!$db_ffo->save())
        {
            throw new ServiceErrorSaveException();
        }
    }
    
    public static function getRoleDetail($id)
    {
        return FfoData::getRawById($id);
    }
}