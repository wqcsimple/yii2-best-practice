<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "ffo_data".
 *
 * @property integer $id
 * @property string $item_id
 * @property string $name
 * @property integer $type
 * @property string $avatar
 * @property integer $price
 * @property integer $level
 * @property string $add_time
 * @property string $images
 * @property string $data
 * @property string $comment
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class FfoData extends \yii\db\ActiveRecord implements ModelApiInterface 
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ffo_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'type', 'level', 'weight', 'create_time', 'update_time'], 'integer'],
            [['images', 'data', 'add_time'], 'string'],
            [['item_id', 'name'], 'string', 'max' => 99],
            [['avatar', 'comment'], 'string', 'max' => 999],
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
            'name' => 'Name',
            'type' => 'Type',
            'avatar' => 'Avatar',
            'price' => 'Price',
            'level' => 'Level',
            'add_time' => 'Add Time',
            'images' => 'Images',
            'data' => 'Data',
            'comment' => 'Comment',
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
            'name' => 'Name',
            'type' => 'Type',
            'avatar' => 'Avatar',
            'price' => 'Price',
            'level' => 'Level',
            'add_time' => 'Add Time',
            'images' => 'Images',
            'data' => 'Data',
            'comment' => 'Comment',
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
            'name' => 'Name',
            'type' => 'Type',
            'avatar' => 'Avatar',
            'price' => 'Price',
            'level' => 'Level',
            'add_time' => 'Add Time',
            'images' => 'Images',
            'data' => 'Data',
            'comment' => 'Comment',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'item_id' => 's',
            'name' => 's',
            'type' => 'i',
            'avatar' => 's',
            'price' => 'i',
            'level' => 'i',
            'add_time' => 's',
            'images' => 's',
            'data' => 's',
            'comment' => 's',
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
            $model['img_list'] = array_filter(explode(',', $model['images']));
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
     * @return array|null|\yii\db\ActiveRecord | \app\models\FfoData;
     */
    public static function findById($id) 
    {
        return self::find()->where(" weight >= 0 ")->andWhere(["id" => $id])->one();
    }
    
    public static function getRawById($id)
    {
        return self::processRawDetail(self::findById($id));
    }
}
