<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "item_price".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $price
 * @property string $img
 * @property string $desc
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class ItemPrice extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'price', 'weight', 'create_time', 'update_time'], 'integer'],
            [['img', 'desc'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'price' => 'Price',
            'img' => 'Img',
            'desc' => "Desc",
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'item_id' => 'Item ID',
            'price' => 'Price',
            'img' => 'Img',
            'desc' => "Desc",
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'item_id' => 'Item ID',
            'price' => 'Price',
            'img' => 'Img',
            'desc' => "Desc",
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'item_id' => 'i',
            'price' => 'i',
            'img' => 's',
            'desc' => "s",
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
        }

        return $model;
    }

    public static function processRawDetail($model)
    {
        $model = self::processRaw($model, self::detailAttributes());

        return $model;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord | \app\models\ItemPrice
     */
    public static function findById($id)
    {
        return self::find()->where(" weight >= 0 ")->andWhere(['id' => $id])->one();
    }

    /**
     * @param $item_id
     * @param int $price
     * @param $img
     * @param $desc
     * @return array | \app\models\ItemPrice
     */
    public static function saveItemPrice($item_id, $price = 0, $img, $desc)
    {
        $item_price = new ItemPrice();
        $item_price->item_id = $item_id;
        $item_price->price = $price * 100;
        $item_price->img = $img;
        $item_price->desc = $desc;
        
        return [$item_price->save(), $item_price];
    }
}
