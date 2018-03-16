<?php

namespace app\modules\client\v100\controllers;

use app\modules\client\v100\services\PostService;

class PostController extends BaseApiController
{

    public function actionSave()
    {
        $this->checkParams(['title', 'content', 'source']);

        $title = strval($this->params['title']);
        $content = strval($this->params['content']);
        $source = strval($this->params['source']);

        $post_id = isset($this->params['post_id']) ? intval($this->params['post_id']) : 0 ;

        $_data = null;
        PostService::savePost($post_id, $title, $content, $source);

        $this->finishSuccess($_data);
    }

    public function actionList()
    {
        $title = isset($this->params['title']) ? strval($this->params['title']) : '' ;

        $_data = null;
        $_data = PostService::getPostList($title);

        $this->finishSuccess($_data);
    }

    public function actionDelete()
    {
        $this->checkParams(['post_id']);

        $post_id = intval($this->params['post_id']);

        $_data = null;
        PostService::deletePost($post_id);

        $this->finishSuccess($_data);
    }

}