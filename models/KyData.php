<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "ky_data".
 *
 * @property integer $id
 * @property string $role
 * @property string $item_id
 * @property string $item_name
 * @property integer $level
 * @property integer $price
 * @property string $add_time
 * @property string $modified_time
 * @property string $data
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class KyData extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ky_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name'], 'required'],
            [['level', 'price', 'weight', 'create_time', 'update_time'], 'integer'],
            [['data'], 'string'],
            [['role', 'item_name', 'add_time', 'modified_time'], 'string', 'max' => 99],
            [['item_id'], 'string', 'max' => 999],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Role',
            'item_id' => 'Item ID',
            'item_name' => 'Item Name',
            'level' => 'Level',
            'price' => 'Price',
            'add_time' => 'Add Time',
            'modified_time' => 'Modified Time',
            'data' => 'Data',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'role' => 'Role',
            'item_id' => 'Item ID',
            'item_name' => 'Item Name',
            'level' => 'Level',
            'price' => 'Price',
            'add_time' => 'Add Time',
            'modified_time' => 'Modified Time',
            'data' => 'Data',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'role' => 'Role',
            'item_id' => 'Item ID',
            'item_name' => 'Item Name',
            'level' => 'Level',
            'price' => 'Price',
            'add_time' => 'Add Time',
            'modified_time' => 'Modified Time',
            'data' => 'Data',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'role' => 's',
            'item_id' => 's',
            'item_name' => 's',
            'level' => 'i',
            'price' => 'i',
            'add_time' => 's',
            'modified_time' => 's',
            'data' => 's',
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
}
