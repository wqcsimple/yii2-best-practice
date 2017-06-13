<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/12/17
 * Time: 7:01 PM
 */
namespace app\controllers;

use yii\web\Controller;

class FfoController extends Controller
{
    public $layout = "todc";
    
    const URL_PREFIX = "https://tieba.baidu.com";
    const FFO_URL = "https://tieba.baidu.com/f?kw=%E8%87%AA%E7%94%B1%E5%B9%BB%E6%83%B3&ie=utf-8&pn=";
    const FFO_BUY_URL = "https://tieba.baidu.com/f?kw=ffo%E4%BA%A4%E6%98%93&ie=utf-8&pn=";
    
    public function actionIndex()
    {
        $ffo_list = $this->getItemListByuUrl(self::FFO_URL);
        $ffo_buy_list = $this->getItemListByuUrl(self::FFO_BUY_URL);
        
        return $this->render('/ffo/index', [
            'ffo_list' => $ffo_list,
            'ffo_buy_list' => $ffo_buy_list
        ]);
    }
    
    private function getItemListByuUrl($url)
    {
        $item_list = [];

        $i = 0;
        while ($i < 50)
        {
            $url = $url . $i;

            \phpQuery::newDocumentFile($url);

            $title_list =  pq(".threadlist_title");
            foreach ($title_list as $title_item)
            {
                $href = pq($title_item)->find('a')->attr('href');
                $title = pq($title_item)->find('a')->text();

                $item = [
                    'href' => self::URL_PREFIX . $href,
                    'title' => $title
                ];

                $item_list[] = $item;
            }

            $i += 50;
        }
        
        return $item_list;
    }
}