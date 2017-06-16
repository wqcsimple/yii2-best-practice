<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/16/17
 * Time: 3:34 PM
 */
namespace app\controllers;

use Caxy\HtmlDiff\HtmlDiff;
use yii\web\Controller;

class TypingController extends Controller
{
    public $layout = 'typing';
    
    public function actionIndex()
    {
        return $this->render('/typing/index');    
    }
    
    public function actionSubmit()
    {
        $origin_content = app()->request->post('origin_content');
        $content = app()->request->post('content');

        $htmlDiff = new HtmlDiff($origin_content, $content);
        
        $content = $htmlDiff->build();
        
        return $this->render('/typing/result', ['content' => $content]);
    }
}