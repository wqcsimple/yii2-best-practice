<?php

namespace app\services;

use app\components\AsyncTaskRunner;
use app\components\DXConst;
use app\components\DXUtil;
use app\models\Company;
use app\models\Station;
use app\models\Transport;
use app\models\UserAddress;
use app\models\Waybill;
use app\models\WaybillStatus;
use Elasticsearch\ClientBuilder;
use Overtrue\Pinyin\Pinyin;

class SearchConfigService extends \yii\base\Object
{
    static $client;

    /**
     * @return \Elasticsearch\Client
     */
    private static function getClient()
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



    public static function resetIndex()
    {
        $client = self::getClient();

        SearchIndexService::deleteAllIndex();

        echo "create index\r\n";

        try
        {
            $client->indices()->create([
                'index' => DXConst::KEY_SEARCH_INDEX,
                'body' => [
                    "settings" => [
                        "analysis" => [
                            "analyzer" => [
                                "pinyin_analyzer" => [
                                    "type" => "custom",
                                    "tokenizer" => "pinyin_tokenizer"
                                ]
                            ],
                            "tokenizer" => [
                                "pinyin_tokenizer" => [
                                    "type" => "ngram",
                                    "min_gram" => 2,
                                    "max_gram" => 2,
                                    "token_chars" => [
                                        "letter",
                                        "digit"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "mappings" => [
                        "company" => [
                            "properties" => [
                                "name_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                            ]
                        ],
                        "address" => [
                            "properties" => [
                                "name_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "province_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "city_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "county_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "company.name_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                            ]
                        ],
                        "region" => [
                            "properties" => [
                                "province_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "city_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "county_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                            ]
                        ],
                        "transport" => [
                            "properties" => [
                                "from_station.name_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                                "to_station.name_pinyin_abbr" => [ "type" => "text", "analyzer" => "pinyin_analyzer" ],
                            ]
                        ]
                    ]
                ]
            ]);
        }
        catch (\Exception $e)
        {
            echo "create index error:\r\n";
            echo $e->getMessage();
            echo "\r\n";
        }
    }

    public static function setAddressIndexConfig()
    {
        $client = self::getClient();
        $client->indices()->close(['index' => DXConst::KEY_SEARCH_INDEX]);
        $client->indices()->putSettings([
           'index' => '',
           'body' => [

           ]
        ]);
    }
}