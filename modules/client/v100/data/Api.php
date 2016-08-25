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


        return array_merge($contact_actions);
    }


}