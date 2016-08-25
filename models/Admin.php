<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use dix\base\component\PasswordHash;
use Yii;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property integer $gender
 * @property string $email
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class Admin extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender', 'weight', 'create_time', 'update_time'], 'integer'],
            [['username', 'name', 'email'], 'string', 'max' => 99],
            [['password'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'gender' => 'Gender',
            'email' => 'Email',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'gender' => 'Gender',
            'email' => 'Email',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'gender' => 'Gender',
            'email' => 'Email',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'username' => 's',
            'password' => 's',
            'name' => 's',
            'gender' => 'i',
            'email' => 's',
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
     * @return array|null|\yii\db\ActiveRecord | \app\models\Admin
     */
    public static function findById($id)
    {
        return self::find()->where(' weight >= 0 ')->andWhere(['id' => $id])->one();
    }

    public static function getRawById($id)
    {
        return self::processRaw(self::findById($id));
    }

    /**
     * @param $username
     * @return array|null|\yii\db\ActiveRecord | \app\models\Admin
     */
    public static function findByUsername($username)
    {
        if (!$username) return null;

        return self::find()->where(" weight >= 0 ")->andWhere(['username' => $username])->one();
    }

    public static function encodePassword($password)
    {
        return PasswordHash::create_hash($password);
    }

    public function validatePassword($password)
    {
        return PasswordHash::validate_password($password, $this->password);
    }
}
