<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $content
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class Contact extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'name', 'phone', 'email'], 'string'],
            [['weight', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => "Content",
            'name' => "Name",
            'phone' => "Contact",
            'email' => "Email",
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'content' => "Content",
            'name' => "Name",
            'phone' => "Contact",
            'email' => "Email",
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
            'id' => 'ID',
            'content' => "Content",
            'name' => "Name",
            'phone' => "Contact",
            'email' => "Email",
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'content' => "s",
            'name' => "s",
            'phone' => "s",
            'email' => "s",
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
     * @return array|null|\yii\db\ActiveRecord | \app\models\Contact
     */
    public static function findById($id)
    {
        return self::find()->where(' weight >= 0 ')->andWhere(['id' => $id])->one();
    }

    public static function getRawById($id)
    {
        return self::processRaw(self::findById($id));
    }
}
