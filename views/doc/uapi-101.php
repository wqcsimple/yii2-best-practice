<?php
use yii\helpers\Html;
use app\assets\AppAsset;

$host = 'http://api.yuntick.com/101/uapi/';

$actions = [
    [
        'name' => '登录',
        'action' => 'user-login',
        'token' => false,
        'param' => [ 'phone', 'password' ],
        'response' => [ 'code', 'user', 'token' ]
    ],

    [
        'name' => '注册',
        'action' => 'user-register',
        'token' => false,
        'param' => [ 'phone', 'password', 'code(验证码)' ],
        'response' => [ 'code', 'message' ]
    ],

    [
        'name' => '登出',
        'action' => 'user-logout',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code' ]
    ],  

    [
        'name' => '获得用户基本信息',
        'action' => 'user-info',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code', 'courier' ]
    ],  

    [
        'name' => '修改密码',
        'action' => 'user-password-update',
        'token' => true,
        'param' => [ 'old_password', 'password' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '重设密码',
        'action' => 'user-password-reset',
        'token' => false,
        'param' => [ 'phone', 'password', 'code' ],
        'response' => [ 'code', 'message' ]
    ],      

    [
        'name' => '更新头像',
        'action' => 'user-avatar-update',
        'token' => true,
        'param' => [ 'img(头像文件名，需调用照片上传接口上传照片后获得)' ],
        'response' => [ 'code' ]
    ],  

    [
        'name' => '基本信息更新',
        'action' => 'user-basic-info-update',
        'token' => true,
        'param' => [ 'name', 'email', 'gender', 'birthday(生日时间戳)' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '绑定手机号更新，注意一定要验证手机号，这个方法里不检查',
        'action' => 'user-phone-update',
        'token' => true,
        'param' => [ 'phone' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '发送手机验证码',
        'action' => 'phone-verification-code-send',
        'token' => true,
        'param' => [ 'phone' ],
        'response' => [ 'code' ]
    ],   

    [
        'name' => '检查手机验证码',
        'action' => 'phone-verification-code-check',
        'token' => true,
        'param' => [ 'phone', 'code' ],
        'response' => [ 'code', 'ok' ]
    ],  

    [
        'name' => '获取用户地址列表',
        'action' => 'user-address-list',
        'token' => true,
        'param' => [ ],
        'response' => [ 'code', 'address_list' ]
    ], 

    [
        'name' => '保存或新增用户发件或收件地址',
        'action' => 'user-address-save',
        'token' => true,
        'param' => [ 'address_id(0表示新增)', 'type(1表示发件地址，2表示收件地址)', 'name', 'province', 'city', 'county', 'address', 'zip', 'phone', 'telephone' ],
        'response' => [ 'code', 'message' ]
    ],                       

    [
        'name' => '删除用户地址',
        'action' => 'user-address-delete',
        'token' => true,
        'param' => [ 'address_id' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '设定某个地址为默认地址',
        'action' => 'user-address-set-as-default',
        'token' => true,
        'param' => [ 'address_id', 'type(1表示发件地址，2表示收件地址)' ],
        'response' => [ 'code', 'message' ]
    ],     

    [
        'name' => '创建快递单',
        'action' => 'waybill-save',
        'token' => true,
        'param' => [ 'from_customer_name', 'from_customer_phone', 'from_customer_province', 'from_customer_city', 'from_customer_county', 'from_customer_address(街道地址)', 'from_customer_longitude', 'from_customer_latitude',
            'to_customer_name', 'to_customer_phone', 'to_customer_province', 'to_customer_city', 'to_customer_county', 'to_customer_address(街道地址)', 'to_customer_longitude', 'to_customer_latitude',
            'content', 'comment(备注)', 'length', 'width', 'height', 'weight',
            'insure(是否保价)', 'insure_declared_value(保价声明价值)', 'insure_rate(保价费率)', 'insure_charge(保价费用)',
            'freight', 'bonus(小费)', 'charge(总价)' ],
        'response' => [ 'code', 'status_list' ]
    ], 

    [
        'name' => '呼叫单个快递员',
        'action' => 'call-courier',
        'token' => true,
        'param' => [ 'waybill_id', 'courier_id', 'longitude', 'latitude', 'province', 'city', 'county', 'address' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '呼叫附近快递员，10KM内',
        'action' => 'call-nearby-courier',
        'token' => true,
        'param' => [ 'waybill_id', 'longitude', 'latitude', 'province', 'city', 'county', 'address' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '获得附近快递员列表，10KM内',
        'action' => 'courier-list-nearby',
        'token' => true,
        'param' => [ 'longitude', 'latitude' ],
        'response' => [ 'code', 'courier_list' ]
    ], 

    [
        'name' => '获得快递公司列表',
        'action' => 'express-org-list',
        'token' => true,
        'param' => [ ],
        'response' => [ 'code', 'express_org_list' ]
    ], 

    [
        'name' => '按距离搜索快递员',
        'action' => 'courier-list-search-by-distance',
        'token' => true,
        'param' => [ 'longitude', 'latitude', 'distance' ],
        'response' => [ 'code', 'courier_list' ]
    ], 

    [
        'name' => '按快递公司搜索快递员',
        'action' => 'courier-list-search-by-express-org',
        'token' => true,
        'param' => [ 'longitude', 'latitude', 'org_id' ],
        'response' => [ 'code', 'courier_list' ]
    ],  

    [
        'name' => '按好评搜索快递员',
        'action' => 'courier-list-search-by-rate',
        'token' => true,
        'param' => [ 'longitude', 'latitude' ],
        'response' => [ 'code', 'courier_list' ]
    ],  

    [
        'name' => '获得快递员详细信息',
        'action' => 'courier-info',
        'token' => true,
        'param' => [ 'courier_id' ],
        'response' => [ 'code', 'courier' ]
    ], 

    [
        'name' => '获得快递员位置信息',
        'action' => 'courier-info',
        'token' => true,
        'param' => [ 'courier_id' ],
        'response' => [ 'code', 'longitude', 'latitude' ]
    ],  

    [
        'name' => '查看快递员评价列表',
        'action' => 'courier-info',
        'token' => true,
        'param' => [ 'courier_id', 'offset', 'length' ],
        'response' => [ 'code', 'rate_list' ]
    ],   

    [
        'name' => '评价某次快递配送或者取件',
        'action' => 'waybill-rate-save',
        'token' => true,
        'param' => [ 'waybill_id', 'type(1表示取件，2表示派件)', 'courier_id', 'attitude', 'speed', 'rate', 'content' ],
        'response' => [ 'code', 'message' ]
    ],                                         

    [
        'name' => '快递单收单列表',
        'action' => 'waybill-receive-list',
        'token' => true,
        'param' => [ 'page(从1开始)' ],
        'response' => [ 'code', 'waybill_list' ]
    ], 

    [
        'name' => '快递单送单列表',
        'action' => 'waybill-deliver-list',
        'token' => true,
        'param' => [ 'page(从1开始)' ],
        'response' => [ 'code', 'waybill_list' ]
    ],   

    [
        'name' => '快递单详情',
        'action' => 'waybill-info',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'waybill' ]
    ], 

    [
        'name' => '快递单照片列表',
        'action' => 'waybill-img-list',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'waybill_img_list' ]
    ],  

    [
        'name' => '更新已取件',
        'action' => 'waybill-status-update-receive-success',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'message' ]
    ],   

    [
        'name' => '更新取消发件',
        'action' => 'waybill-status-update-cancel',
        'token' => true,
        'param' => [ 'waybill_id', 'reason' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '更新已派件（客户已收件）',
        'action' => 'waybill-status-update-deliver-success',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '新增投诉',
        'action' => 'waybill-complain-save',
        'token' => true,
        'param' => [ 'waybill_id', 'text', 'img' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '快递单投诉列表',
        'action' => 'waybill-complain-list',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'waybill_complain_list' ]
    ],  

    [
        'name' => '修改代理收件人',
        'action' => 'waybill-to-customer-agent-save',
        'token' => true,
        'param' => [ 'waybill_id', 'name', 'phone', 'province', 'city', 'county', 'address', 'longitude', 'latitude' ],
        'response' => [ 'code', 'message' ]
    ],      

    [
        'name' => '修改快递员收件小费',
        'action' => 'waybill-bonus-receive-update',
        'token' => true,
        'param' => [ 'waybill_id', 'bonus' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '修改快递员派件小费',
        'action' => 'waybill-bonus-deliver-update',
        'token' => true,
        'param' => [ 'waybill_id', 'bonus', 'time' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '获得常用发件人列表',
        'action' => 'user-address-list-from',
        'token' => true,
        'param' => [ ],
        'response' => [ 'code', 'address_list' ]
    ],  

    [
        'name' => '获得常用收件人列表',
        'action' => 'user-address-list-to',
        'token' => true,
        'param' => [ ],
        'response' => [ 'code', 'address_list' ]
    ],   

    [
        'name' => '获得常用收件人列表',
        'action' => 'user-address-list-to',
        'token' => true,
        'param' => [ ],
        'response' => [ 'code', 'address_list' ]
    ],                                                         

    [
        'name' => '给快递员发送即时信息',
        'action' => 'courier-message-send',
        'token' => true,
        'param' => [ 'courier_id', 'content_type', 'content' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '快递单拍照照片保存',
        'action' => 'waybill-img-save',
        'token' => true,
        'param' => [ 'waybill_id', 'img' ],
        'response' => [ 'code', 'message' ]
    ],                                                                 

    [
        'name' => '新增反馈',
        'action' => 'feedback-save',
        'token' => true,
        'param' => [ 'content', 'phone', 'address', 'alipay_id' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '检查更新',
        'action' => 'check-update',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code', 'version', 'version_number', 'url' ]
    ],  

    [
        'name' => '创建支付',
        'action' => 'waybill-pay-create',
        'token' => true,
        'param' => [ 'waybill_id', 'target_type', 'sn', 'charge', 'bonus', 'type', 'content' ],
        'response' => [ 'code', 'sign', 'prepay_id', 'nonce_str' ]
    ],   

    [
        'name' => '查看快递单支付状态',
        'action' => 'waybill-pay-status',
        'token' => true,
        'param' => [ 'waybill_id', 'target_type' ],
        'response' => [ 'code', 'status' ]
    ],  

    [
        'name' => '查看支付信息',
        'action' => 'pay-info',
        'token' => true,
        'param' => [ 'id' ],
        'response' => [ 'code', 'pay' ]
    ],                 



];

?>
<style>

* { font-family: Consolas; }
* { font-family: Courier New; }

td.name { width: 200px; }
</style>

<blockquote>
<p><?= $host ?></p>
<p>请求方法: POST</p>
<p>基本参数: token, client, version</p>
<p>返回数据格式: json，必包含code，code为0表示请求成功，code不为0表示请求失败</p>
<p>错误返回格式: { 'code': 1, 'message': 'some error' }</p>
</blockquote>
<div class="clear-20"></div>

<?
foreach ($actions as $item)
{
    $name = $item['name'];
    $action = $item['action'];
    $token = $item['token'];
    $param = $item['param'];
    $response = $item['response'];
?>
    <h4><?= $name ?></h4>
    <table class="table table-condensed">
        <tr class="">
            <td class="name">action</td>
            <td class="green bold"><?= $action ?></td>
        </tr>
        <tr>
            <td class="name">param</td>
            <td class=""><?= implode(', ', $param) ?></td>
        </tr>
        <tr>
            <td class="name">response</td>
            <td><?= implode(', ', $response) ?></td>
        </tr>
    </table>
    <div class="clear-20"></div>
<?
}
?>
