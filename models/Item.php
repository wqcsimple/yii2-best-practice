<?php

namespace app\models;

use app\components\DXUtil;
use app\modules\client\v100\services\ItemPriceService;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property string $name
 * @property integer $min_price
 * @property integer $max_price
 * @property integer $ave_price
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class Item extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min_price', 'max_price', 'ave_price', 'weight', 'create_time', 'update_time'], 'integer'],
            [['name'], 'string', 'max' => 99],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'ave_price' => 'Ave Price',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'name' => 'Name',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'ave_price' => 'Ave Price',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'name' => 'Name',
            'min_price' => 'Min Price',
            'max_price' => 'Max Price',
            'ave_price' => 'Ave Price',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'name' => 's',
            'min_price' => 'i',
            'max_price' => 'i',
            'ave_price' => 'i',
            'weight' => 'i',
            'create_time' => 'i',
            'update_time' => 'i',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            $this->update_time = time();
            if ($insert)
            {
                $this->create_time = $this->update_time;
            }
            
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function processRaw($model, $keys = null)
    {
        $keys = $keys ?: self::basicAttributes();
        $types = self::attributeTypes();
        $model = DXUtil::processModel($model, $keys, $types);

        if ($model)
        {
//            $model['price_list'] = ItemPriceService::getItemPriceListByItemId($model['id']);
        }

        return $model;
    }
    
    public static function processRawDetail($model)
    {
        $model = self::processRaw($model, self::detailAttributes());

        return $model;
    }

    public static function processForAdmin($model, $keys = null)
    {
        $keys = $keys ?: self::basicAttributes();
        $types = self::attributeTypes();
        $model = DXUtil::processModel($model, $keys, $types);

        if ($model)
        {
            $model['item_count'] = ItemPriceService::getItemPriceCount($model['id']);
        }

        return $model;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord | \app\models\Item
     */
    public static function findById($id)
    {
        return self::find()->where(" weight >= 0 ")->andWhere(["id" => $id])->one();
    }

    /**
     * @param $id
     * @return array|null
     */
    public static function getRawById($id)
    {
        return self::processRaw(self::findById($id));
    }
    
    public function updateMinPrice()
    {
        $min_price = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['item_id' => $this->id])->min("price");
        $min_price = $min_price ?: 0;
        
        $this->min_price = $min_price;
        
        $this->save();
    }
    
    public function updateMaxPrice()
    {
        $max_price = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['item_id' => $this->id])->max("price");
        $max_price = $max_price ?: 0;
        
        $this->max_price = $max_price;
        
        $this->save();
    }
    
    public function updateAvePrice()
    {
        $ave_price = ItemPrice::find()->where(" weight >= 0 ")->andWhere(['item_id' => $this->id])->average("price");
        $ave_price = $ave_price ?: 0;
        
        $this->ave_price = intval($ave_price);
        
        $this->save();
    }
}
