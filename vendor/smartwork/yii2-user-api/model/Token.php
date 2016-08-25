<?php

namespace smartwork\user\api\model;

use dix\base\component\DXLog;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "token".
 *
 * @property integer $id
 * @property string $token
 * @property integer $type
 * @property integer $version
 * @property integer $status
 * @property integer $user_id
 * @property integer $expire_time
 * @property integer $create_time
 * @property integer $update_time
 */
class Token extends \yii\db\ActiveRecord
{
    const CLIENT_TYPE_IOS     = 1;
    const CLIENT_TYPE_ANDROID = 2;
    const CLIENT_TYPE_AIR     = 3;

    const TYPE_USER = 1;
    const TYPE_ADMIN = 2;

    const STATUS_INVALID = 0;
    const STATUS_VALID   = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'user_id', 'expire_time', 'create_time', 'update_time'], 'integer'],
            [['token'], 'string', 'max' => 99]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'type' => 'Type',
            'status' => 'Status',
            'user_id' => 'User ID',
            'expire_time' => 'Expire Time',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function generateToken()
    {
        mt_srand((double)microtime() * 10000);
        $key = md5(md5(uniqid(rand(), true)) . time());

        return $key;
    }

    public static function makeToken($type, $status, $user_id)
    {
        $token = new Token();
        $token->token = self::generateToken();
        $token->type = $type;
        $token->status = $status;
        $token->user_id = intval($user_id);
        $token->create_time = time();
        $token->update_time = $token->create_time;
        $token->expire_time = 0;

        return [$token->save(), $token];
    }
    
    /**
     * @param $token
     * @param $type
     * @return array|null|\yii\db\ActiveRecord | \app\models\Token
     */
    public static function findValidTokenByToken($token, $type)
    {
        return self::find()->where(['token' => $token, 'type' => $type, 'status' => self::STATUS_VALID])->one();
    }

    public static function makeUserTokenInvalid($user_id, $type)
    {
        self::updateAll(['status' => self::STATUS_INVALID], ['user_id' => intval($user_id), 'type' => intval($type)]);
    }

    public function updateTime()
    {
        try
        {
            $this->update_time = time();
            $this->save();
        }
        catch (\Exception $e)
        {
            DXLog::debug('update_token_update_time_exception', $e->getTraceAsString());
        }
    }
}
