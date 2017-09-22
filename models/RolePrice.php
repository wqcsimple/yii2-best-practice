<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "role_price".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $item_price_id
 * @property string $comment
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class RolePrice extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'item_price_id', 'weight', 'create_time', 'update_time'], 'integer'],
            [['comment'], 'string', 'max' => 99],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'item_price_id' => 'Item Price ID',
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
            'role_id' => 'Role ID',
            'item_price_id' => 'Item Price ID',
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
            'role_id' => 'Role ID',
            'item_price_id' => 'Item Price ID',
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
            'role_id' => 'i',
            'item_price_id' => 'i',
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
        }

        return $model;
    }

    public static function processRawDetail($model)
    {
        $model = self::processRaw($model, self::detailAttributes());

        return $model;
    }
}
