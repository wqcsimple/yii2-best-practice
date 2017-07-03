<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/29/17
 * Time: 4:07 PM
 */
namespace app\modules\client\v100\services;

use app\components\DXConst;
use app\components\DXUtil;
use app\models\Post;
use dix\base\exception\ServiceErrorSaveException;

class PostService {

    public static function savePost($post_id, $title, $content, $source)
    {
        $post = Post::findById($post_id);
        if (!$post)
        {
            $post = new Post();
            $post->weight = DXConst::WEIGHT_NORMAL;
        }
        
        $post->title = $title;
        $post->content = $content;
        $post->source = $source;
        
        if (!$post->save())
        {
            throw new ServiceErrorSaveException('save error', ['errors' => $post->errors]);
        }
    }

    public static function getPostList($title)
    {
        $query = Post::find()->where(" weight >= 0 ");
        
        if ($title)
        {
            $query->andWhere(['like', 'title', $title]);
        }
        
        $db_post_list = $query->orWhere(['id' => SORT_DESC])->asArray()->all();
        $post_list = DXUtil::formatModelList($db_post_list, Post::class);
        
        return [
            'post_list' => $post_list  
        ];
    }

    public static function deletePost($post_id)
    {
        $post = Post::findById($post_id);
        if ($post)
        {
            $post->weight = DXConst::WEIGHT_DELETED;
            if (!$post->save())
            {
                throw new ServiceErrorSaveException('save error', ['error' => $post->errors]);
            }
        }
    }
}