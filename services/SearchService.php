<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 5/15/17
 * Time: 11:58 AM
 */
namespace app\services;

use Elasticsearch\ClientBuilder;

class SearchService
{
    static $client;
    
    public static function getClient()
    {
        if (!self::$client)
        {
            $hosts = param('elasticsearch')['hosts'];
            self::$client = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        }

        return self::$client;
    }
}