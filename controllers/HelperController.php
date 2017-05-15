<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/4/16
 * Time: 4:52 PM
 */
namespace app\controllers;

use app\services\SearchService;
use yii\web\Controller;

class HelperController extends Controller
{
    public function actionIndex()
    {
        $client = SearchService::getClient();

        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id',
            'body' => ['testField' => 'abc']
        ];

        $response = $client->index($params);
        
        dump($response);
    }
    
    public function actionGet()
    {
        $client = SearchService::getClient();
        
        $key = 'b';
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            ['match' => ['testField' => 'abc1']],   
                            ['wildcard' => ['testField' => "*$key*"]],   
                            ['match' => ['testField' => 'c']],  
                        ]
                    ] 
                ]
            ]
        ];

        $response = $client->search($params);
        dump($response);
    }
    
}