<?php

namespace app\services;

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

class SearchService
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

    public static function processResult($response)
    {
//         dump($response);

        $raw_item_list = isset($response['hits']['hits']) ? $response['hits']['hits'] : [];
        $item_list = [];
        foreach ($raw_item_list as $item) {
            $item_list[] = $item['_source'];
        }

        $count = $response['hits']['total'];
        $time = $response['took'];
        $timeout = $response['timed_out'];

        return [$count, $item_list, $time, $timeout];
    }

    // 订单搜索
    public static function searchWaybill(
        $sp,
        $page,
        $filter = null,
        $type = Waybill::TYPE_INTRA_CITY,
        $begin_date = null,
        $end_date = null,
        $sn = null,
        $status = null,
        $route_id = null,
        $from_company_name = null,
        $to_company_name = null,
        $charge_done = null,
        $cod_done = null,
        $cod_paid_done = null,
        $company_id = null,
        $express_company_id = null,
        $express_branch_id = null,
        $is_return = null,
        $return_status = null,
        $source_type = null,
        $skip_status_init = null,
        $charge_pay_type = null
    )
    {
        $query = self::prepareQueryWaybillListForAdmin($sp, $filter, $type, $begin_date, $end_date, $sn, $status, $route_id, $from_company_name, $to_company_name, $charge_done, $cod_done, $cod_paid_done, $company_id, $express_company_id, $express_branch_id, $is_return, $return_status, $source_type, $skip_status_init, $charge_pay_type);

        $page = intval($page);
        $page = $page < 1 ? 1 : intval($page);
        $size = 10;

        // $count = intval($query->count());

        // $waybill_list = DXUtil::formatModelList($db_waybill_list, Waybill::className(), 'processRawForAdmin');

        $client = self::getClient();

        $params = [
            'index' => 'yex',
            'type' => 'waybill',
            'body' => [
                'from' => $page -1,
                'size' => $size,
                'query' => [
                    'bool' => [
                        'must' => $query
                    ]
                ],
                'sort' => [
                    [ 'create_time' => [ 'order' => 'desc' ] ]
                ]
            ]
        ];


        list ($count, $item_list, $time, $timeout) = self::processResult($client->search($params));
        $waybill_list = DXUtil::formatModelList($item_list, Waybill::className(), 'processRawForAdmin');

        return [
            'count' => $count,
            'waybill_list' => $waybill_list,
            'time' => $time,
            'timeout' => $timeout,
             // 'query' => $query,
        ];
    }

    /**
     * @param null $sp
     * @param null $filter
     * @param null $type
     * @param null $begin_date
     * @param null $end_date
     * @param null $sn
     * @param null $status
     * @param null $route_id
     * @param null $from_company_name
     * @param null $to_company_name
     * @param null $charge_done
     * @param null $cod_done
     * @param null $cod_paid_done
     * @param null $company_id
     * @param null $express_company_id
     * @param null $express_branch_id
     * @param null $is_return
     * @param null $return_status
     * @param null $source_type
     * @param null $skip_status_init
     * @param null $charge_pay_type
     * @return array|null
     */
    public static function prepareQueryWaybillListForAdmin(
        $sp,
        $filter = null,
        $type = null,
        $begin_date = null,
        $end_date = null,
        $sn = null,
        $status = null,
        $route_id = null,
        $from_company_name = null,
        $to_company_name = null,
        $charge_done = null,
        $cod_done = null,
        $cod_paid_done = null,
        $company_id = null,
        $express_company_id = null,
        $express_branch_id = null,
        $is_return = null,
        $return_status = null,
        $source_type = null,
        $skip_status_init = null,
        $charge_pay_type = null
    )
    {
        $query = null;

        if ($type == Waybill::TYPE_CROSS_CITY)
        {
            $sp = DXConst::SP_QPC;
        }
        if ($type == Waybill::TYPE_INTRA_CITY)
        {
            $sp = DXConst::SP_YUNTO;
        }

        // $query = Waybill::find()->andWhere(['sp' => $sp])->andWhere(' weight >= 0 ')->andWhere(['type' => $type]);
        $query = [
            [ 'term' => [ 'sp' => $sp ] ],
            [ 'term' => [ 'type' => $type ] ],
            [ 'range' => [ 'weight' => [ 'gte' => 0 ] ] ],
        ];

        if (DXUtil::isInt($status)) {
            // $query->andWhere(['status' => intval($status)]);

            $query[] = [ 'term' => [ 'status' => intval($status) ] ];
        }
        if ($begin_date) {
            // $query->andWhere(['>=', 'create_time', strtotime($begin_date)]);

            $query[] = [ 'range' => [ 'create_time' => [ 'gte' => strtotime($begin_date) ] ] ];
        }
        if ($end_date) {
            // $query->andWhere(['<=', 'create_time', strtotime($end_date) + 86400]);

            $query[] = [ 'range' => [ 'create_time' => [ 'lte' => strtotime($end_date) + 86400 ] ] ];
        }

        if ($sn) {
            if ($sp == DXConst::SP_QPC) {
                // $query->andWhere(['like', 'express_waybill_id', $sn]);
                if ($type == Waybill::TYPE_CROSS_CITY)
                {
                    $query[] = [ 'wildcard' => [ 'express_waybill_id' => "*$sn*" ] ];
                }
                else if ($type == Waybill::TYPE_INTRA_CITY)
                {
                    $query[] = [ 'wildcard' => [ 'sn' => "*$sn*" ] ];
                }
            } else {
                // $query->andWhere(['or', ['like', 'sn', $sn], ['like', 'express_waybill_id', $sn]]);
                $query[] = [
                    'bool' => [
                        'should' => [
                            [ 'wildcard' => [ 'express_waybill_id' => "*$sn*" ] ],
                            [ 'wildcard' => [ 'sn' => "*$sn*" ] ],
                        ]
                    ]
                ];
            }

            // $query->andWhere(['>=', 'create_time', time() - 86400 * 60]);
        }
        if ($route_id) {
            // $query->andWhere(['route_id' => intval($route_id)]);

            $query[] = [ 'term' => [ 'route_id' => intval($route_id) ] ];
        }
        if ($from_company_name) {
            // $company_id_list = CompanyService::getCompanyIdList($from_company_name);
            // $query->andWhere(['in', 'from_company_id', $company_id_list]);

            // $query->andWhere(['or', ['like', 'from_company_name', $from_company_name], ['like', 'from_name', $from_company_name]]);
            // $query->andWhere(['>=', 'create_time', time() - 86400 * 21]);

            $query[] = [
                'bool' => [
                    'should' => [
                        [ 'wildcard' => [ 'from_company_name' => "*$from_company_name*" ] ],
                        [ 'wildcard' => [ 'from_name' => "*$from_company_name*" ] ],
                    ]
                ]
            ];
        }
        if ($to_company_name) {
            // $company_id_list = CompanyService::getCompanyIdList($to_company_name);
            // $query->andWhere(['in', 'to_company_id', $company_id_list]);

            // $query->andWhere(['or', ['like', 'to_company_name', $to_company_name], ['like', 'to_name', $to_company_name]]);
            // $query->andWhere(['>=', 'create_time', time() - 86400 * 21]);

            $query[] = [
                'bool' => [
                    'should' => [
                        [ 'wildcard' => [ 'to_company_name' => "*$to_company_name*" ] ],
                        [ 'wildcard' => [ 'to_name' => "*$to_company_name*" ] ],
                    ]
                ]
            ];
        }
        if (DXUtil::isInt($charge_done)) {
            $charge_done = intval($charge_done);
            if ($charge_done != 0) {
                // $query->andWhere(' freight_paid >= charge ');

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => "doc['freight_paid'].value >= doc['charge].value"
                            ]
                        ]
                    ]
                ];
            } else {
                // $query->andWhere(' freight_paid < charge ');

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => "doc['freight_paid'].value < doc['charge].value"
                            ]
                        ]
                    ]
                ];
            }

        }
        if (DXUtil::isInt($cod_done)) {
            $cod_done = intval($cod_done);
            if ($cod_done != 0) {
                // $query->andWhere(' cod_received >= cod ');

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => "doc['cod_received'].value >= doc['cod].value"
                            ]
                        ]
                    ]
                ];
            } else {
                // $query->andWhere(' cod_received < cod ');

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => "doc['cod_received'].value < doc['cod].value"
                            ]
                        ]
                    ]
                ];
            }
        }
        if (DXUtil::isInt($cod_paid_done)) {
            $cod_paid_done = intval($cod_paid_done);
            if ($cod_paid_done != 0) {
                // $query->andWhere(' cod_received > 0 && cod_paid >= cod_received ');

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => "doc['cod_received'].value > 0 && doc['cod_paid'].value >= doc['cod_received].value"
                            ]
                        ]
                    ]
                ];

            } else {
                // $query->andWhere(' cod_received > 0 && cod_paid < cod_received ');

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => "doc['cod_received'].value > 0 && doc['cod_paid'].value < doc['cod_received].value"
                            ]
                        ]
                    ]
                ];
            }
        }

        if ($company_id) {
            $company_id = intval($company_id);
            // $query->andWhere('from_company_id = :company_id_1 or to_company_id = :company_id_2', [':company_id_1' => $company_id, ':company_id_2' => $company_id]);

            $query[] = [
                'bool' => [
                    'should' => [
                        [ 'term' => [ 'from_company_id' => $company_id ] ],
                        [ 'term' => [ 'to_company_id' => $company_id ] ],
                    ]
                ]
            ];
        }

        if ($express_company_id) {
            // $query->andWhere(['express_company_id' => $express_company_id]);

            $query[] = [ 'term' => [ 'express_company_id' => intval($express_company_id) ] ];
        }

        if ($express_branch_id) {
            // $express_branch_id = intval($express_branch_id);
            // $query->andWhere(['express_branch_id' => $express_branch_id]);

            $query[] = [ 'term' => [ 'express_branch_id' => intval($express_branch_id) ] ];
        }

        if ($is_return) {
            // $query->andWhere(['<>', 'return_status', Waybill::RETURN_STATUS_NONE]);

            $query[] = [
                'bool' => [
                    'must_not' => [
                        [ 'term' => [ 'return_status' => Waybill::RETURN_STATUS_NONE ] ],
                    ]
                ]
            ];
        }

        if ($return_status) {
            /// $query->andWhere(['return_status' => $return_status]);

            $query[] = [ 'term' => [ 'return_status' => intval($return_status) ] ];
        }

        if (DXUtil::isInt($filter)) {
            $filter = intval($filter);

            if ($filter == 1) {
//                $query->andWhere('
//                    (freight_paid > 0 and (status = :status_cancel or status = :status_receive_reject or freight_paid <> charge))
//                    or
//                    (cod_received > 0 and (status = :status_deliver_reject or cod_received <> cod))
//                ', [
//                    ':status_cancel' => WaybillStatus::STATUS_CANCEL,
//                    ':status_receive_reject' => WaybillStatus::STATUS_RECEIVE_REJECT,
//                    ':status_deliver_reject' => WaybillStatus::STATUS_DELIVER_REJECT
//                ]);

                $query[] = [
                    'bool' => [
                        'filter' => [
                            'script' => [
                                'script' => [
                                    'inline' => "
                                        (doc['freight_paid'].value > 0 && (status = :status_cancel or status = :status_receive_reject || doc['freight_paid'].value != doc['charge'].value))
                                        || 
                                        (doc['cod_received'].value > 0 && (status = :status_deliver_reject || doc['cod_received'].value <> doc['cod'].value))
                                    ",
                                    'params' => [
                                        ':status_cancel' => WaybillStatus::STATUS_CANCEL,
                                        ':status_receive_reject' => WaybillStatus::STATUS_RECEIVE_REJECT,
                                        ':status_deliver_reject' => WaybillStatus::STATUS_DELIVER_REJECT
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        if (DXUtil::isInt($source_type)) {
            // $query->andWhere(['source_type' => $source_type]);

            $query[] = [ 'term' => [ 'source_type' => intval($source_type) ] ];
        }

        if ($skip_status_init) {
            // $query->andWhere(['<>', 'status', WaybillStatus::STATUS_INIT]);

            $query[] = [
                'bool' => [
                    'must_not' => [
                        [ 'term' => [ 'status' => WaybillStatus::STATUS_INIT ] ],
                    ]
                ]
            ];
        }

        if (DXUtil::isInt($charge_pay_type)) {
            // $query->andWhere(['charge_pay_type' => $charge_pay_type]);

            $query[] = [ 'term' => [ 'charge_pay_type' => intval($charge_pay_type) ] ];
        }

        return $query;
    }

    public static function buildIndexOfWaybill($i = 0, $limit = 0)
    {
        $client = self::getClient();
        $page_size = 20;

        while (true)
        {
            echo "build waybill index ". $i . "\r\n";

            if ($limit > 0 && $i * $page_size >= $limit)
            {
                break;
            }

            $waybill_list = Waybill::find()
                ->orderBy(['create_time' => SORT_DESC])
                ->offset($i * $page_size)->limit($page_size)->asArray()->all();
            if (empty($waybill_list))
            {
                break;
            }

            foreach ($waybill_list as $waybill)
            {
                /**
                 * @var \app\models\Waybill $waybill
                 */

                // $model = DXUtil::formatRawModel($waybill, Waybill::className(), Waybill::basicAttributes());
                // $model = $waybill->attributes;

                $client->index([
                    'index' => 'yex',
                    'type' => 'waybill',
                    'id' => $waybill['id'],
                    'body' => $waybill
                ]);
            }

            $waybill_list = null;
            gc_collect_cycles();

            $i++;
            sleep(5);
        }
    }

    public static function searchCompany($sp, $key)
    {
        $client = self::getClient();

        $params = [
            'index' => 'yex',
            'type' => 'company',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [ 'term' => [ 'sp' => $sp ] ],
                            [
                                'bool' => [
                                    'should' => [
                                        [ 'wildcard' => [ 'pinyin_abbr' => "*$key*" ] ],
                                        [
                                            'match' => [
                                                'name' => $key,
                                            ]
                                        ],
                                        [ 'wildcard' => [ 'code' => "*$key*" ] ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];


        list ($count, $item_list, $time, $timeout) = self::processResult($client->search($params));
        $company_list = DXUtil::formatModelList($item_list, Company::className(), 'processSimpleRawForInstantSearch');
        return [
            'count' => $count,
            'company_list' => $company_list,
            'time' => $time,
            'timeout' => $timeout,
        ];
    }

    public static function searchAddress($sp, $key)
    {
        $client = self::getClient();

        $params = [
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'address',
            'body' => [
                'from' => 0,
                'size' => 10,
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'name' => $key, ] ],
                            [ 'match' => [ 'name_pinyin_abbr' => $key ] ],
                            [ 'match' => [ 'province_pinyin_abbr' => $key ] ],
                            [ 'match' => [ 'city_pinyin_abbr' => $key ] ],
                            [ 'match' => [ 'county_pinyin_abbr' => $key ] ],
                            [ 'match' => [ 'phone' => $key ] ],
                            [ 'match' => [ 'telephone' => $key ] ],


                            [ 'match' => [ 'company.name' => $key ] ],
                            [ 'match' => [ 'company.phone' => $key ] ],

                        ]
                    ]
                ]
            ]
        ];
        

        list ($count, $item_list, $time, $timeout) = self::processResult($client->search($params));
        $address_list = DXUtil::formatModelList($item_list, UserAddress::className(), 'processSimpleRaw');
        return [
            'count' => $count,
            'company_list' => $address_list,
            'time' => $time,
            'timeout' => $timeout,
        ];
    }

    public static function searchRegion($sp, $key)
    {
        $client = self::getClient();

//        $keys = array_filter(explode(' ', $key));
//        $query = [];
//        foreach ($keys as $key)
//        {
//            if (trim($key) === '')
//            {
//                continue;
//            }
//
//            $condition = [
//                [ 'match' => [ 'province' => $key, ] ],
//                [ 'match' => [ 'province_pinyin_abbr' => $key ] ],
//                [ 'wildcard' => [ 'province_pinyin_abbr' => "*$key*" ] ],
//
//                [ 'match' => [ 'city' => $key, ] ],
//                [ 'match' => [ 'city_pinyin_abbr' => $key ] ],
//                [ 'wildcard' => [ 'city_pinyin_abbr' => "*$key*" ] ],
//
//                [ 'match' => [ 'county' => $key, ] ],
//                [ 'match' => [ 'county_pinyin_abbr' => $key ] ],
//                [ 'wildcard' => [ 'county_pinyin_abbr' => "*$key*" ] ],
//            ];
//
//            $query[] = [
//                'bool' => [ 'should' => $condition ]
//            ];
//        }

        $params = [
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'region',
            'body' => [
                'from' => 0,
                'size' => 10,
                '_source' => ['province', 'city', 'county'],
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'province' => $key, ] ],
                            [ 'match' => [ 'province_pinyin_abbr' => $key ] ],
//                            [ 'match' => [ 'province_pinyin_abbr_1' => $key ] ],
                            [ 'match' => [ 'city' => $key, ] ],
                            [ 'match' => [ 'city_pinyin_abbr' => $key ] ],
//                            [ 'match' => [ 'city_pinyin_abbr_1' => $key ] ],
                            [ 'match' => [ 'county' => $key, ] ],
                            [ 'match' => [ 'county_pinyin_abbr' => $key ] ],
//                            [ 'match' => [ 'county_pinyin_abbr_1' => $key ] ],
                        ]
                    ]
                ]
            ]
        ];

        list ($count, $item_list, $time, $timeout)  = self::processResult($client->search($params));
        return [
            'count' => $count,
            'address_list' => $item_list,
            'time' => $time,
            'timeout' => $timeout,
        ];
    }

    public static function searchTransport($sp, $key)
    {
        $client = self::getClient();

        $params = [
            'index' => DXConst::KEY_SEARCH_INDEX,
            'type' => 'transport',
            'body' => [
                'from' => 0,
                'size' => 130,
                'query' => [
                    'bool' => [
                        'must' => [
                            [   
                                'term' => [ 'sp' => $sp ] 
                            ],
                            [
                                'range' => [
                                    "create_time" => [
                                        "gte" => strtotime('today')
                                    ]
                                ],  
                            ],
                            [
                                'bool' => [
                                    'should' => [
                                        [ 'match' => [ 'from_station.name' => $key, ] ],
                                        [ 'match' => [ 'from_station.name_pinyin_abbr' => $key, ] ],
                                        [ 'wildcard' => [ 'from_station.name_pinyin_abbr' => "*$key*" ] ],
                                        [ 'match' => [ 'to_station.name' => $key ] ],
                                        [ 'match' => [ 'to_station.name_pinyin_abbr' => $key ] ],
                                        [ 'wildcard' => [ 'to_station.name_pinyin_abbr' => "*$key*" ] ],

                                        [ 'match' => [ 'start_hour' => $key ] ],
                                        [ 'match' => [ 'start_minute' => $key ] ],
                                        
                                    ]   
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ];

        list ($count, $item_list, $time, $timeout)  = self::processResult($client->search($params));

        $transport_list = [];
        foreach ($item_list as $item)
        {
            $transport = Transport::processSimpleRaw($item);
            $transport['from_station'] = Station::processSimpleRaw(isset($item['from_station']) ? $item['from_station'] : []);
            $transport['to_station'] = Station::processSimpleRaw(isset($item['to_station']) ? $item['to_station'] : []);
            $transport_list[] = $transport;
        }

        return [
            'count' => $count,
            'transport_list' => $transport_list,
            'time' => $time,
            'timeout' => $timeout,
        ];
    }



}