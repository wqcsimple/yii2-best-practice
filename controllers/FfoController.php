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
        $ffo_list = $this->getItemListByUrl(self::FFO_URL);
        $ffo_buy_list = $this->getItemListByUrl(self::FFO_BUY_URL);
        $ck_buy_list = $this->getItemListByUrlForCk(self::FFO_BUY_URL);

        return $this->render('/ffo/index', [
            'ffo_list' => $ffo_list,
            'ffo_buy_list' => $ffo_buy_list,
            "ck_buy_list" => $ck_buy_list
        ]);
    }
    
    private function getItemListByUrl($url)
    {
        $item_list = [];

        $i = 0;
        while ($i < 50)
        {
            $url = $url . $i;

            $response = curl('GET', $url);
            \phpQuery::newDocumentHTML($response['response']);

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

    private function getItemListByUrlForCk($url)
    {
        $item_list = [];

        $i = 0;
        while ($i < 50)
        {
            $url = $url . $i;

            $response = curl('GET', $url);
            \phpQuery::newDocumentHTML($response['response']);

            $title_list =  pq(".threadlist_title");
            foreach ($title_list as $title_item)
            {
                $href = pq($title_item)->find('a')->attr('href');
                $title = pq($title_item)->find('a')->text();

                $item = [
                    'href' => self::URL_PREFIX . $href,
                    'title' => $title
                ];

                if (strpos($title, '刺客') > 0 || strpos($title, 'CK') > 0 || strpos($title, 'ck') > 0) {
                    $item_list[] = $item;
                }
            }

            $i += 50;
        }

        return $item_list;
    }
}