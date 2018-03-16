<?php

namespace app\models;

use app\components\DXKey;
use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use dix\base\component\Redis;
use Yii;

/**
 * This is the model class for table "pay".
 *
 * @property integer $id
 * @property string $sn
 * @property integer $parent_id
 * @property integer $target_id
 * @property integer $target_type
 * @property integer $operator_id
 * @property integer $operator_type
 * @property integer $channel
 * @property integer $money
 * @property integer $status
 * @property string $data
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class Pay extends \yii\db\ActiveRecord implements ModelApiInterface
{
    const CHANNEL_ALIPAY = 1;   // 支付宝
    const CHANNEL_WXPAY = 2;    // 微信支付
    
    
    // 支付状态
    const STATUS_INIT = 0;
    const STATUS_WAIT = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = -1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'target_id', 'target_type', 'operator_id', 'operator_type', 'channel', 'money', 'status', 'weight', 'create_time', 'update_time'], 'integer'],
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
            'sn' => 'Sn',
            'parent_id' => 'Parent ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'operator_id' => 'Operator ID',
            'operator_type' => 'Operator Type',
            'channel' => 'Channel',
            'money' => 'Money',
            'status' => 'Status',
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
            'sn' => 'Sn',
            'parent_id' => 'Parent ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'operator_id' => 'Operator ID',
            'operator_type' => 'Operator Type',
            'channel' => 'Channel',
            'money' => 'Money',
            'status' => 'Status',
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
            'sn' => 'Sn',
            'parent_id' => 'Parent ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'operator_id' => 'Operator ID',
            'operator_type' => 'Operator Type',
            'channel' => 'Channel',
            'money' => 'Money',
            'status' => 'Status',
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
            'sn' => 's',
            'parent_id' => 'i',
            'target_id' => 'i',
            'target_type' => 'i',
            'operator_id' => 'i',
            'operator_type' => 'i',
            'channel' => 'i',
            'money' => 'i',
            'status' => 'i',
            'data' => 's',
            'weight' => 'i',
            'create_time' => 'i',
            'update_time' => 'i',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->update_time = time();
            if ($insert) {
                $this->create_time = $this->update_time;
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

        if ($model) {
        }

        return $model;
    }

    public static function processRawDetail($model)
    {
        $model = self::processRaw($model, self::detailAttributes());

        return $model;
    }

    public static function findById($id)
    {
        return self::find()->where(['id' => $id])->andWhere(' weight >= 0 ')->one();
    }

    public static function findBySN($sn)
    {
        return self::find()->where(['sn' => $sn])->andWhere(' weight >= 0 ')->one();
    }

    public static function snExists($sn)
    {
        return self::find()->where(' LOWER(sn) = :sn ', [':sn' => strtolower($sn)])->exists();
    }

    public static function makeSN($waybill_id)
    {
        $time = date('YmdHis', time());
        $redis = Redis::client();
        $key = DXKey::getKeyOfPayCountOfToday();
        $count = $redis->incr($key);

        $sn = $time . '6' . $count . '8' . $waybill_id . '9' . DXUtil::generateRandomNumberString(20);
        $sn = substr($sn, 0, 32);

        return $sn;
    }

}
