<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 5/26/15
 * Time: 23:41
 */
namespace app\modules\client\v100\data;

class Api
{
    const TYPE_DATA = 'Data';
    const TYPE_FFO = "FFO";
    const TYPE_COMMON = "common";

    public static function PathGuestCanAccess()
    {
        return [
        ];
    }

    public static function ActionList()
    {
        

        
        $contact_actions = [
            [
                'type' => self::TYPE_DATA,
                'name' => 'data - save',
                'action' => 'data/save',
                'token' => false,
                'params' => ['data_id | i, 0', 'name | s', 'price | i', 'img | s, null', 'desc | s, null'],
                'response' => [
                    'data_id' => '\app\modules\client\v100\services\DataService::saveData($data_id, $name, $price, $img, $desc)',
                ]
            ],

            [
                'type' => self::TYPE_DATA,
                'name' => 'data - list',
                'action' => 'data/list',
                'token' => false,
                'params' => ['page | i, 1', 'name | s, null', 'min_price | i, null', 'max_price | i, null'],
                'response' => '\app\modules\client\v100\services\DataService::dataList($page, $name, $min_price, $max_price)',
            ],
            
            [
                'type' => self::TYPE_DATA,
                'name' => 'data - detail',
                'action' => 'data/detail',
                'token' => false,
                'params' => ['data_id | i'],
                'response' => [
                    'detail' => '\app\modules\client\v100\services\DataService::detail($data_id)',
                ]    
            ],
            
            [
                'type' => self::TYPE_DATA,
                'name' => 'data - delete',
                'action' => 'data/delete',
                'token' => false,
                'params' => ['data_id | i'],
                'response' => '\app\modules\client\v100\services\DataService::deleteData($data_id)',
            ],
            
            [
                'type' => self::TYPE_DATA,
                'name' => 'data - delete Price',
                'action' => 'data/delete-item-price',
                'token' => false,
                'params' => ['item_price_id | i'],
                'response' => '\app\modules\client\v100\services\DataService::deleteItemPrice($item_price_id)',
            ],

            [
                'type' => self::TYPE_DATA,
                'name' => 'data - item Price List',
                'action' => 'data/price-list',
                'token' => false,
                'params' => ['item_id | i'],
                'response' => '\app\modules\client\v100\services\DataService::getItemPriceList($item_id)',
            ],

            [
                'type' => self::TYPE_DATA,
                'name' => 'data - item price save',
                'action' => 'data/save-item-price',
                'token' => false,
                'params' => ['item_id | i', 'price | i, null', 'img | s, null', 'desc | s, null'],
                'response' => '\app\modules\client\v100\services\DataService::saveItemPrice($item_id, $price, $img, $desc)',
            ],
        ];
        
        $ffo_actions = [

            [
                'type' => self::TYPE_FFO,
                'name' => 'FFO - role list',
                'action' => 'ffo/role-list',
                'token' => false,
                'params' => ['page | i, 1', 'type | i, null', 'min_price | i, null', 'max_price | i, null'],
                'response' => '\app\modules\client\v100\services\FfoService::getRoleList($page, $type, $min_price, $max_price)',
            ],
            
            [
                'type' => self::TYPE_FFO,
                'name' => 'FFO - role save',
                'action' => 'ffo/role-save',
                'token' => false,
                'params' => ['item_id | s', 'name | s', 'type | i', 'avatar | s', 'price | i, 0', 'level | i, 0', 'add_time | s', 'comment | s, null'],
                'response' => '\app\modules\client\v100\services\FfoService::saveRole($item_id, $name, $type, $avatar, $price, $level, $add_time, $comment)',
            ],


            [
                'type' => self::TYPE_FFO,
                'name' => 'FFO - role delete',
                'action' => 'ffo/role-delete',
                'token' => false,
                'params' => ['id | i'],
                'response' => [
                    "null" => '\app\modules\client\v100\services\FfoService::deleteRole($id)',
                ]
            ],

            [
                'type' => self::TYPE_FFO,
                'name' => 'FFO - role detail',
                'action' => 'ffo/role-detail',
                'token' => false,
                'params' => ['id | i'],
                'response' => [
                    "role" => '\app\modules\client\v100\services\FfoService::getRoleDetail($id)',
                ]
            ],
            
            [
                'type' => self::TYPE_FFO,
                'name' => 'FFO - role img save',
                'action' => 'ffo/role-img-save',
                'token' => false,
                'params' => ['id | i', 'img | s'],
                'response' => [
                    "null" => '\app\modules\client\v100\services\FfoService::saveRoleImg($id, $img)',
                ]
            ],

            [
                'type' => self::TYPE_FFO,
                'name' => 'FFO - role data save',
                'action' => 'ffo/role-data-save',
                'token' => false,
                'params' => ['id | i', 'data | s'],
                'response' => [
                    "null" => '\app\modules\client\v100\services\FfoService::saveRoleData($id, $data)',
                ]
            ],
        ];
        
        $common_actions = [
              [
                  'type' => self::TYPE_COMMON,
                  'name' => 'common - send-phone',
                  'action' => 'common/phone-verification-code-send',
                  'token' => false,
                  'params' => ['phone | s'],
                  'response' => [
                      "null" => '\app\modules\client\v100\services\CommonService::sendPhoneVCode($phone)',
                  ]
              ]
        ];


        return array_merge($contact_actions, $ffo_actions, $common_actions);
    }


}