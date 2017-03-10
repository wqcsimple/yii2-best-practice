<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 3/10/17
 * Time: 3:36 PM
 */
namespace app\controllers;

use app\components\DXUtil;
use app\models\Pay;
use app\services\PayService;

class PayController
{
    public function actionAlipayNotify()
    {
        $config = DXUtil::param('alipay');
        $partner = $config['partner'];

        $config['partner'] = $partner;
        $config['private_key_path'] = realpath('../pay/alipay/rsa_private_key.pem');
        $config['ali_public_key_path'] = realpath('../pay/alipay/alipay_public_key.pem');
        $config['sign_type'] = strtoupper('RSA');
        $config['input_charset'] = strtolower('utf-8');
        $config['cacert'] = realpath('../pay/alipay/cacert.pem');
        $config['transport'] = 'http';

        $this->processAlipayNotify($config);
    }

    public function actionWxpayNotifyYunto()
    {
        require_once('../pay/wxpay/lib/WxPay.Api.php');
        require_once('../pay/wxpay/lib/WxPay.Notify.php');

        $this->processWxpayNotify();
    }

    public function processAlipayNotify($config)
    {
        require_once('../pay/alipay/lib/alipay_notify.class.php');

        /**
         * @var \app\models\Pay $pay
         */

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($config);
        $verify_result = $alipayNotify->verifyNotify();

        if ($verify_result)
        {
            $out_trade_no = $_POST['out_trade_no'];
            $pay = Pay::findOne(['sn' => $out_trade_no]);
            PayService::addPayNotification(Pay::CHANNEL_ALIPAY, $out_trade_no, json_encode($_POST), $pay);
            if ($pay)
            {
                $pay->data = json_encode($_POST);
                $trade_status = $_POST['trade_status'];
                switch ($trade_status)
                {
                    case 'TRADE_FINISHED':
                    {
                        $pay->status = Pay::STATUS_SUCCESS;
                    } break;

                    case 'TRADE_SUCCESS':
                    {
                        $pay->status = Pay::STATUS_SUCCESS;
                    } break;

                    case 'WAIT_BUYER_PAY':
                    {
                        $pay->status = Pay::STATUS_WAIT;
                    } break;
                }
                if (in_array($trade_status, ['TRADE_FINISHED', 'TRADE_SUCCESS']) && $pay->save())
                {
                    $this->processPay($pay);
                }
            }
            
            echo 'success'; // 请不要修改或删除
        }
        else 
        {
            echo "fail";
        }
    }

    private function processWxpayNotify()
    {
        /**
         * @var \app\models\Pay $pay
         */

        //获取通知的数据
        $xml = file_get_contents("php://input");
        //如果返回成功则验证签名
        $data = false;
        try
        {
            $data = \WxPayResults::Init($xml);
        }
        catch (\WxPayException $e)
        {
            $msg = $e->errorMessage();
        }

        if ($data === false)
        {
            $notify = new \WxPayNotify();
            $notify->SetReturn_code("FAIL");
            $notify->SetReturn_msg('校验失败');
            $notify->ReplyNotify(false);
        }
        else
        {
            // $this->log('wx-pay-check-sign-ok', $data);

            $success = true;
            $message = 'OK';
            if (!array_key_exists("transaction_id", $data)
                || !array_key_exists("out_trade_no", $data)
                || !array_key_exists("result_code", $data))
            {
                $message = "输入参数不正确";
                $success = false;
            }
            //查询订单，判断订单真实性
            $notify = new \WxPayNotify();
            if (!$notify->QueryOrder($data["transaction_id"]))
            {
                $message = "订单查询失败";
                $success = false;
            }

            $out_trade_no = $data['out_trade_no'];
            $pay = Pay::findOne(['sn' => $out_trade_no]);
            PayService::addPayNotification(Pay::CHANNEL_WXPAY, $out_trade_no, json_encode($data), $pay);
            if ($pay)
            {
                $pay_data = @json_decode($pay->data, true);
                if (!is_array($pay_data))
                {
                    $pay_data = [];
                }
                $pay_data['pay'] = $data;
                $pay->data = json_encode($pay_data);
                $trade_status = $data['result_code'];
                switch ($trade_status)
                {
                    case 'SUCCESS':
                    {
                        $pay->status = Pay::STATUS_SUCCESS;
                    } break;

                    case 'FAIL':
                    {
                        $pay->status = Pay::STATUS_FAIL;
                    } break;
                }
                if (in_array($trade_status, ['SUCCESS', 'FAIL']) && $pay->save())
                {
                    $this->processPay($pay);
                }
            }

            // $this->log('wx-pay-notify-result', $message);
            if ($success)
            {
                $notify->SetReturn_code("SUCCESS");
                $notify->SetReturn_msg("OK");
            }
            else
            {
                $notify->SetReturn_code("FAIL");
                $notify->SetReturn_msg($message);
            }
            $notify->ReplyNotify(false);
        }
    }

    /**
     * @param \app\models\Pay $pay
     */

    public function processPay($pay)
    {
        PayService::processPayAfterNotification($pay);
    }

    
}