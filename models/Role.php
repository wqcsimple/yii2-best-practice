<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use dix\base\exception\ServiceErrorNotExistsException;
use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property integer $money
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class Role extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'weight', 'create_time', 'update_time'], 'integer'],
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
            'money' => 'Money',
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
            'money' => 'Money',
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
            'money' => 'Money',
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
            'money' => 'i',
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
     * @param $role_id
     * @return array|null|\yii\db\ActiveRecord | \app\models\Role
     */
    public static function findById($role_id)
    {
        return self::find()->where(" weight >= 0 ")->andWhere(['id' => $role_id])->one();
    }

    /**
     * @param $role_id
     * @return Role|array|null|\yii\db\ActiveRecord | \app\models\Role
     * @throws ServiceErrorNotExistsException
     */
    public static function findOrFail($role_id)
    {
        $role = self::findById($role_id);
        if (!$role) 
        {
            throw new ServiceErrorNotExistsException();
        }
        
        return $role;
    }
    
}
