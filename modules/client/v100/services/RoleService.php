<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/29/17
 * Time: 4:07 PM
 */
namespace app\modules\client\v100\services;


use app\components\DXConst;
use app\components\DXUtil;
use app\exceptions\ServiceSaveFailException;
use app\models\ItemPrice;
use app\models\Role;
use app\models\RolePrice;
use dix\base\exception\ServiceErrorSaveException;

class RoleService {

    public static function getRoleList()
    {
        $db_role_list = Role::find()->where(" weight >= 0 ")->orderBy(['id' => SORT_DESC])->asArray()->all();
     
        return [
            'list' => DXUtil::formatModelList($db_role_list, Role::className())  
        ];
    }
    
    public static function saveRole($role_id, $name)
    {
        $role = Role::findById($role_id);
        if (!$role) 
        {
            $role = new Role();
            $role->weight = DXConst::WEIGHT_NORMAL;
        }
        
        $role->name = $name;
        if (!$role->save()) 
        {
            throw new ServiceSaveFailException('save error', ['errors' => $role->errors]);
        }
    }

    public static function deleteRole($role_id)
    {
        $role = Role::findById($role_id);
        if ($role) 
        {
            $role->weight = DXConst::WEIGHT_DELETED;
            $role->save();
        }
    }

    public static function addRolePrice($role_id, $item_price_id)
    {
        $role = Role::findOrFail($role_id);
     
        $item_price = ItemPrice::findOrFail($item_price_id);
        
        $role_price = new RolePrice();
        $role_price->weight = DXConst::WEIGHT_NORMAL;
        $role_price->role_id = $role->id;
        $role_price->item_price_id = $item_price->id;
        if (!$role_price->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $role_price->errors]);
        }
     
        self::updateRoleMoney($role->id);
    }

    private static function updateRoleMoney($role_id)
    {
        $role = Role::findOrFail($role_id);
        $db_item_price_id_list = RolePrice::find()->select('item_price_id')->where(" weight >= 0 ")->asArray()->all();
        
        $item_price_id_list = [];
        foreach ($db_item_price_id_list as $db_item_price_id)
        {
            $item_price_id_list[] = intval32bits($db_item_price_id['item_price_id']);
        }
        
        $sum_money = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['in', 'id', $item_price_id_list])->sum("rmb");
        
        $role->money = intval($sum_money);
        $role->save();
    }

    public static function deleteRolePrice($role_price_id)
    {
        $item_price = ItemPrice::findOrFail($role_price_id);
        $item_price->weight = DXConst::WEIGHT_DELETED;
        $item_price->save();
    }


}