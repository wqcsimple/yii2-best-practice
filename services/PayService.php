<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 3/10/17
 * Time: 2:03 PM
 */
namespace app\services;

use app\components\DXUtil;
use app\exceptions\ServiceSaveFailException;
use app\models\Pay;
use app\models\PayNotification;
use dix\base\exception\ServiceErrorWrongParamException;

class PayService
{
    /**
     * 支付前操作
     * @param $target_id
     * @param $target_type
     * @param $operator_id
     * @param $operator_type
     * @param $channel
     * @param $money
     * @return null
     * @throws ServiceErrorWrongParamException
     * @throws ServiceSaveFailException
     */
    public static function payBeforeOperation($target_id, $target_type, $operator_id, $operator_type, $channel, $money)
    {   
        if (!in_array($channel, [Pay::CHANNEL_ALIPAY, Pay::CHANNEL_WXPAY]))
        {
            throw new ServiceErrorWrongParamException('参数错误: channel');
        }
        
        $sn = Pay::makeSN($target_id);
        $pay = self::preparePay($sn, 0, $target_id, $target_type, $operator_id, $operator_type, $channel, $money);
        if (!$pay->save()) {
            throw new ServiceSaveFailException('发起支付失败', ['errors' => $pay->errors]);
        }
        $pay->refresh();

        $data = null;
        switch ($channel)
        {
            case Pay::CHANNEL_ALIPAY:
                $data['alipay'] = self::payWaybillByAlipay($pay);
                break;
                
            case Pay::CHANNEL_WXPAY:
                $data['wxpay'] = self::payWaybillByWxpay($pay);
                break;
        }

        return $data;
    }

    public static function preparePay($sn, $parent_id, $target_id, $target_type, $operator_id, $operator_type, $channel, $money)
    {
        $pay = Pay::find()->where(['sn' => $sn])->one();
        if (!$pay)
        {
            $pay = new Pay();
        }

        $pay->sn = $sn;
        $pay->parent_id = $parent_id;
        $pay->target_id = $target_id;
        $pay->target_type = $target_type;
        $pay->operator_id = $operator_id;
        $pay->operator_type = $operator_type;
        $pay->channel = $channel;
        $pay->money = $money;
        $pay->status = Pay::STATUS_INIT;

        return $pay;
    }
    
    /**
     * @param Pay $pay
     * @return array
     * @throws ServiceSaveFailException
     */
    private static function payWaybillByAlipay($pay)
    {
        require_once('../pay/alipay/lib/alipay_rsa.function.php');
        require_once('../pay/alipay/lib/alipay_core.function.php');
        
        $config = DXUtil::param('alipay');
        
        $partner = $config['partner'];
        $seller_id = $config['seller_id'];
        $notify_url = $config['notify_url'];
        $rsa_private_key = $config['rsa_private_key'];
        $subject = $config['subject'];
        $body = $config['body'];

        $money = sprintf('%.2f', intval($pay->money) * 1.0 / 100);

        $order = 'partner="'. $partner .'"&seller_id="'. $seller_id .'"&out_trade_no="'. $pay->sn .'"&subject="'. $subject .'"&body="'. $body .'"&total_fee="'. $money .'"&notify_url="'. $notify_url .'"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="30m"';
        $sign = urlencode(rsaSign($order, $rsa_private_key));
        $order =  $order . '&sign="'. $sign .'"&sign_type="RSA"';

        $pay_data = [
            'prepay' => $order
        ];
        $pay->data = DXUtil::jsonEncode($pay_data);

        if (!$pay->save())
        {
            throw new ServiceSaveFailException('发起支付失败', ['errors' => $pay->errors]);
        }

        return [
            'order' => $order
        ];
    }

    /**
     * @param Pay $pay
     */
    private static function payWaybillByWxpay($pay)
    {
        require_once('../pay/wxpay/lib/WxPay.Api.php');

        $config = DXUtil::param('wxpay');

        $appid = $config['appid'];
        $partnerid = $config['partnerid'];
        $notify_url = $config['notify_url'];

        $order = new \WxPayUnifiedOrder();
        $order->SetOut_trade_no($pay->sn);
        $order->SetBody('订单支付');
        $order->SetTotal_fee('' . $pay->money);
        $order->SetTrade_type('APP');
        $order->SetNotify_url($notify_url);

        // $order->SetSpbill_create_ip('219.82.49.49');
        $response_data = \WxPayApi::unifiedOrder($order);

        $data = [];
        if (isset($response_data['sign'])
            && isset($response_data['prepay_id'])
            && isset($response_data['nonce_str'])
            && isset($response_data['nonce_str']))
        {
            $data['prepayid'] = $response_data['prepay_id'];
            $data['noncestr'] = $response_data['nonce_str'];
            $data['appid'] = $appid;
            $data['partnerid'] = $partnerid;
            $data['package'] = 'Sign=WXPay';
            $data['timestamp'] = time();

            $pay_result = \WxPayResults::InitFromArray($data, true);
            $pay_result->SetSign();
            $data = $pay_result->GetValues();
        }
        else
        {
            throw new ServiceSaveFailException('发起微信支付失败');
        }

        $pay_data = [
            'prepay' => $response_data
        ];
        $pay->data = DXUtil::jsonEncode($pay_data);

        if (!$pay->save())
        {
            throw new ServiceSaveFailException('发起支付失败', ['errors' => $pay->errors]);
        }

        return $data;
    }
    
    /**
     * @param $channel
     * @param $sn
     * @param $data
     * @param \app\models\Pay $pay
     */
    public static function addPayNotification($channel, $sn, $data, $pay)
    {
        $pay_notification = new PayNotification();
        $pay_notification->channel = $channel;
        $pay_notification->sn = $sn;
        $pay_notification->data = $data;
        if (!empty($pay))
        {
            $pay_notification->pay_id = $pay->id;
            $pay_notification->target_id = $pay->target_id;
            $pay_notification->target_type = $pay->target_type;
        }

        $pay_notification->save();
    }

    public static function processPayAfterNotification($pay)
    {
        if ($pay == null) return;

        if ($pay->status == Pay::STATUS_SUCCESS)
        {
            // todo 支付成功后的操作
        }
    }

}