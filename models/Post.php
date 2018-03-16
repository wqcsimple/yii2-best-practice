<?php

namespace app\models;

use app\components\DXUtil;
use dix\base\component\ModelApiInterface;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $source
 * @property integer $weight
 * @property integer $create_time
 * @property integer $update_time
 */
class Post extends \yii\db\ActiveRecord implements ModelApiInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['weight', 'create_time', 'update_time'], 'integer'],
            [['title'], 'string', 'max' => 99],
            [['source'], 'string', 'max' => 999],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'source' => 'Source',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function basicAttributes()
    {
        return array_keys([
           'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'source' => 'Source',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function detailAttributes()
    {
        return array_keys([
           'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'source' => 'Source',
            'weight' => 'Weight',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ]);
    }

    public static function attributeTypes()
    {
        return [
            'id' => 'i',
            'title' => 's',
            'content' => 's',
            'source' => 's',
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
     * @param $post_id
     * @return array|null|\yii\db\ActiveRecord | \app\models\Post
     */
    public static function findById($post_id)
    {
        return self::find()->where(" weight >= 0 ")->andWhere(['id' => $post_id])->one();
    }
    
}
