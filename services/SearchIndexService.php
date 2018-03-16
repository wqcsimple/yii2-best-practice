<?php

namespace app\services;

use app\components\AsyncTaskRunner;
use app\components\DXConst;
use app\models\Company;
use app\models\Station;
use app\models\Transport;
use app\models\UserAddress;
use app\models\Waybill;
use app\models\WaybillStatus;
use Elasticsearch\ClientBuilder;
use Overtrue\Pinyin\Pinyin;

class SearchIndexService extends \yii\base\Object
{
    static $client;

    const ROUTE = 'search.index.';

    public static function registerAsyncTask()
    {
        AsyncTaskRunner::registerAsyncTask([
            ['route' => self::ROUTE, 'function' => 'buildCompanyIndex', 'call' => self::className() . "::buildCompanyIndex",],
            ['route' => self::ROUTE, 'function' => 'buildCompanyAddressIndex', 'call' => self::className() . "::buildCompanyAddressIndex",],
            ['route' => self::ROUTE, 'function' => 'buildTransportIndex', 'call' => self::className() . "::buildTransportIndex",],
        ]);
    }

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

    public static function deleteAllIndex()
    {
        $client = self::getClient();

        try
        {
            $client->indices()->delete([
                'index' => DXConst::KEY_SEARCH_INDEX,
            ]);
        }
        catch (\Exception $e)
        {
            echo "delete all index error:\r\n";
            echo $e->getMessage();
            echo "\r\n";
        }
    }



    public static function buildFullCompanyIndex()
    {
        $query = Company::find()->asArray();
        foreach ($query->batch(20) as $i => $item_list)
        {
            $j = 0;
            foreach ($item_list as $item)
            {
                $j++;
                echo "build company index $i - $j \r\n";
                self::buildCompanyIndex($item, null);
            }
        }
    }

    public static function buildCompanyIndex($company, $company_id)
    {
        $client = self::getClient();
        $pinyin = new Pinyin();

        if (!$company || empty($company))
        {
            $company = Company::getRawById(intval($company_id));
            if (!$company)
            {
                return;
            }
        }

        $company['name_pinyin_abbr'] = $pinyin->abbr($company['name']);

        $client->index([
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'company',
            'id' => $company['id'],
            'body' => $company
        ]);
    }

    public static function buildCompanyIndexAsync($company, $company_id)
    {
        AsyncTaskRunner::submitTask(self::ROUTE, 'buildCompanyIndex', [$company, $company_id]);
    }

    public static function deleteCompanyAddressIndex()
    {
        $client = self::getClient();
        $params = [
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'address',
            'body' => [
                'query' => [ 'match_all' => (object)[] ]
            ]
        ];
        $client->deleteByQuery($params);
    }

    public static function buildFullCompanyAddressIndex()
    {
        $query = UserAddress::find()->asArray();
        foreach ($query->batch(20) as $i => $item_list)
        {
            $j = 0;
            foreach ($item_list as $item)
            {
                $j++;
                echo "build company address index $i - $j \r\n";
                self::buildCompanyAddressIndex($item, null);
            }
        }
    }

    public static function buildCompanyAddressIndex($user_address, $user_address_id)
    {
        $client = self::getClient();
        $pinyin = new Pinyin();

        if (!$user_address || empty($user_address))
        {
            $user_address = UserAddress::findById(intval($user_address_id));
            $user_address = UserAddress::processRaw($user_address);
            if (!$user_address)
            {
                return;
            }
        }

        $company_id = $user_address['company_id'];
        $company = Company::getRawById(intval($company_id));
        if (!$company)
        {
            return;
        }
        
        $company['name_pinyin_abbr'] = $pinyin->abbr($company['name']);

        $user_address['company'] = $company;

        $user_address['name_pinyin_abbr'] = $pinyin->abbr($user_address['name']);
        $user_address['province_pinyin_abbr'] = $pinyin->abbr($user_address['province']);
        $user_address['city_pinyin_abbr'] = $pinyin->abbr($user_address['city']);
        $user_address['county_pinyin_abbr'] = $pinyin->abbr($user_address['county']);

        $client->index([
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'address',
            'id' => $user_address['id'],
            'body' => $user_address
        ]);
    }

    public static function buildCompanyAddressIndexAsync($user_address, $user_address_id)
    {
        AsyncTaskRunner::submitTask(self::ROUTE, 'buildCompanyAddressIndex', [$user_address, $user_address_id]);
    }

    public static function buildRegionIndex()
    {
        $client = self::getClient();
        $pinyin = new Pinyin();

        $region_json_file_path = __DIR__ . '/../data/region.json';
        $region_json = file_get_contents(realpath($region_json_file_path));
        $region = json_decode($region_json, true);

        function mb_str_replace($search, $subject, $replace) {
            return implode($replace, mb_split($search, $subject));
        }

        foreach ($region as $code => $name)
        {
            if (intval($code) % 100 == 0)
            {
                continue;
            }

            $province_code = substr($code, 0, 2) . '0000';
            $province = $region[$province_code];
            $province_short = mb_str_replace('省', $province, '');

            $city_code = substr($code, 0, 4) . '00';
            $city = isset($region[$city_code]) ? $region[$city_code] : '';
            $city_short = mb_str_replace('市', $city, '');

            echo $province . ' ' . $city . ' ' . $name . "\r\n";

            $county = $name;
            $county_short = mb_str_replace('自治区', $county, '');
            $county_short = mb_str_replace('区', $county_short, '');
            $county_short = mb_str_replace('自治县', $county_short, '');
            $county_short = mb_str_replace('县', $county_short, '');

            $item = [
                'province' => $province,
                'province_pinyin_abbr' => $pinyin->abbr($province),
                // 'province_pinyin_abbr_1' => $pinyin->abbr($province_short),
                'city' => $city,
                'city_pinyin_abbr' => $pinyin->abbr($city),
                // 'city_pinyin_abbr_1' => $pinyin->abbr($city_short),
                'county' => $county,
                'county_pinyin_abbr' => $pinyin->abbr($county),
                // 'county_pinyin_abbr_1' => $pinyin->abbr($county_short),
            ];
            var_dump($item); echo "\r\n";

            $client->index([
                'index' => DXConst::KEY_SEARCH_INDEX,
                'type' => 'region',
                'id' => $code,
                'body' => $item
            ]);
        }
    }

    public static function buildFullTransportIndex()
    {
        $query = Transport::find()->andWhere(['>=', 'create_time', strtotime('today')])->orderBy(['create_time' => SORT_DESC])->asArray();
        foreach ($query->batch(20) as $i => $item_list)
        {
            $j = 0;
            foreach ($item_list as $item)
            {
                $j++;
                echo "build transport index $i - $j \r\n";
                self::buildTransportIndex($item, null);
            }
        }
    }

    public static function buildTransportIndex($transport, $transport_id)
    {
        $client = self::getClient();
        $pinyin = new Pinyin();

        if (!$transport || empty($transport))
        {
            $transport = Transport::getRawById(intval($transport_id));
            if (!$transport)
            {
                return;
            }
        }

        $from_station = Station::getRawById($transport['from_station_id']);
        if ($from_station) {
            $from_station['name_pinyin_abbr'] = $pinyin->abbr($from_station['name']);
        }

        $to_station = Station::getRawById($transport['to_station_id']);
        if ($to_station) {
            $to_station['name_pinyin_abbr'] = $pinyin->abbr($to_station['name']);
        }

        $transport['from_station'] = $from_station;
        $transport['to_station'] = $to_station;


        $client->index([
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'transport',
            'id' => $transport['id'],
            'body' => $transport
        ]);
    }

    public static function buildTransportIndexAsync($transport, $transport_id)
    {
        AsyncTaskRunner::submitTask(self::ROUTE, 'buildTransportIndex', [$transport, $transport_id]);
    }

}