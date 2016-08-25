<?php

ini_set('date.timezone','Asia/Shanghai');

/**
 * @return \yii\web\Application
 */
function app() 
{
	return \Yii::$app;
}

function dump($target) 
{
    \yii\helpers\VarDumper::dump($target, 10, true);
}

/**
 * @param null $sql
 * @return \yii\db\Command
 */
function sql($sql = null)
{
	$connection = \Yii::$app->db;
	$command = $connection->createCommand($sql);
	
	return $command;
}

/**
 * @return \yii\db\Command
 */
function sqlUser($sql = null)
{
    $connection = \Yii::$app->db_user;
    $command = $connection->createCommand($sql);

    return $command;
}



function makeToken()
{
	mt_srand((double) microtime() * 10000); 
	$key = md5(md5(uniqid(rand(), true)) . time());	
	
	return $key;
}

function param($name) 
{
	return isset(\Yii::$app->params[$name]) ? \Yii::$app->params[$name] : false;
}

function timeFormat($time, $format = 'full') 
{

	if ($time === '')
		return '';

	if ($format == 'ago') 
	{
		$unit = 60;
		$p = time() - $time;
		if ($p / $unit < 1) return ($p / 1) . '秒前';
		
		$unit*=60;
		if ($p / $unit < 1) return intval($p / 60) . '分钟前';
		
		$unit*=24;
		if ($p / $unit < 1) return intval($p / 60 / 60) . '小时前';
		
		$unit*=30;
		if ($p / $unit < 1) return intval($p / 60 / 60 / 24) . '天前';
		
		return timeFormat($time, 'full');
	}
	if ($format == 'full')	return date('Y-m-d H:i:s', $time);
	if ($format == 'date')	return date('Y-m-d', $time);
	if ($format == 'month')	return date('m-d', $time);

    return date('Y-m-d H:i:s', $time);
}

function getConfig($key)
{
	return sql('select value from {{%config}} where `key` = :key ')->bindValues([':key' => $key])->queryScalar();
}

function setConfig($key, $value)
{
	$config = app\models\Config::find()->where(['key' => $key])->one();
	if (!$config)
	{
		$config = new app\models\Config();
		$config->key = $key;
	}
	
	$config->value = $value;



	return $config->save();
}

function url($params)
{
	return app()->urlManager->createUrl($params);
}

function getServerIp()
{
	exec("/sbin/ifconfig | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'", $lines);

	return @$lines[0];
}


function encodeRegion($province, $city, $county)
{
    $data = [];

    $province_code = sql(' select code from {{%region}} where name = :name and level = 1 ')
                        ->bindValues([':name' => $province])
                        ->queryScalar();
    if (!$province)
    {
        return null;
    }
    $province_code = intval($province_code);
    $data['province'] = $province_code;

    $city_code = sql(' select code from {{%region}} where name = :name and level = 2 and code > :min and code < :max ')
                    ->bindValues([':name' => $city, ':min' => $province_code, ':max' => $province_code + 10000])
                    ->queryScalar();
    if (!$city_code)
    {
        return null;
    }
    $city_code = intval($city_code);
    $data['city'] = $city_code;

    $county_code = sql(' select code from {{%region}} where name = :name and level = 3 and code > :min and code < :max ')
                    ->bindValues([':name' => $county, ':min' => $city_code, ':max' => $city_code + 100])
                    ->queryScalar();
    if (!$county_code)
    {
        return null;
    }
    $county_code = intval($county_code);
    $data['county'] = $county_code;

    return $data;
}



function getRegionName($code)
{
	return sql(" select name from {{%region}} where code = $code ")->queryScalar();

	/*
    $redis = new Predis\Client(['scheme' => 'tcp', 'host' => 'dd', 'port'  => 6379]);

    $code = intval($code);

    $key = "express.region.$code";
    $name = $redis->get($key);
    if (!$name)
    {

        $name = sql(" select name from {{%region}} where code = $code ")->queryScalar();
        if ($name)
        {
            $redis->set($key, $name);
        }
    }

    return $name;
    */
}

function curl($method, $url, $post_data = null)
{
    $data['error'] = null;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    if ($method == 'POST' && $post_data != null)
    {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data['response'] = curl_exec($ch);
    if (curl_errno($ch))
    {
        $data['error'] = curl_error($ch);
    }
    curl_close($ch);

    return $data;
}

function curl_wx_ssl($url, $vars)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_TIMEOUT, 30);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch,CURLOPT_SSLCERTTYPE, 'PEM');
    curl_setopt($ch,CURLOPT_SSLCERT, realpath('./pay/wxpay/apiclient_cert.pem'));
    curl_setopt($ch,CURLOPT_SSLKEYTYPE, 'PEM');
    curl_setopt($ch,CURLOPT_SSLKEY, realpath('./pay/wxpay/apiclient_key.pem'));
    curl_setopt($ch,CURLOPT_CAINFO, realpath('./pay/wxpay/rootca.pem'));


    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $vars);

    $data['response'] = curl_exec($ch);
    if (curl_errno($ch))
    {
        $data['error'] = curl_error($ch);
    }
    curl_close($ch);
}

function addZero($number)
{
    $number = intval($number);
    if ($number <= 9)
    {
        return '0' . $number;
    }
    return $number;
}

function redisLog($title, $data, $key = 'express-log')
{
    $log = [
        'time' => timeFormat(time()),
        'title' => $title,
        'data' => $data
    ];
    $redis = new Predis\Client(['scheme' => 'tcp', 'host' => 'redis', 'port'  => 6379]);
    $redis->lpush($key, json_encode($log));
}

function consoleLog($content)
{
    if (defined('IN_CONSOLE_APP'))
    {
        echo timeFormat(time()) . ' ' . strval($content) . "\n";
    }

}