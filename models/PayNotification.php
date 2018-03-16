<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "pay_notification".
 *
 * @property integer $id
 * @property integer $channel
 * @property string $sn
 * @property integer $pay_id
 * @property integer $target_id
 * @property integer $target_type
 * @property string $data
 * @property integer $weight
 * @property integer $create_time
 */
class PayNotification extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel', 'pay_id', 'target_id', 'target_type', 'weight', 'create_time'], 'integer'],
            [['data'], 'required'],
            [['data'], 'string'],
            [['sn'], 'string', 'max' => 99],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel' => 'Channel',
            'sn' => 'Sn',
            'pay_id' => 'Pay ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'data' => 'Data',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'channel' => 'Channel',
            'sn' => 'Sn',
            'pay_id' => 'Pay ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'data' => 'Data',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
            'id' => 'i',
            'channel' => 'i',
            'sn' => 's',
            'pay_id' => 'i',
            'target_id' => 'i',
            'target_type' => 'i',
            'data' => 's',
            'weight' => 'i',
            'create_time' => 'i',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'channel' => 'i',
            'sn' => 's',
            'pay_id' => 'i',
            'target_id' => 'i',
            'target_type' => 'i',
            'data' => 's',
            'weight' => 'i',
            'create_time' => 'i',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->create_time = time();
                $this->data = !empty($this->data) ? $this->data : '';
            }
            return true;
        } else {
            return false;
        }
    }

    public static function processRaw($model, $keys = null)
    {
        $keys = $keys ?: self::basicAttributes();
        $types = self::attributeTypes();
        $model = DXUtil::processModel($model, $keys, $types);

        return $model;
    }

    public static function processRawDetail($model)
    {
        $model = self::processRaw($model, self::detailAttributes());

        return $model;
    }
}
