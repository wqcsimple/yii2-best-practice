<?php
use yii\helpers\Html;
use app\assets\AppAsset;

$host = 'http://api.yuntick.com/101/capi/';

$actions = [
    [
        'name' => '登录',
        'action' => 'courier-login',
        'token' => false,
        'param' => [ 'phone', 'password' ],
        'response' => [ 'code', 'courier', 'token' ]
    ],

    [
        'name' => '注册',
        'action' => 'courier-register',
        'token' => false,
        'param' => [ 'name(姓名)', 'phone', 'password', 'verification(验证码)' ],
        'response' => [ 'code', 'message' ]
    ],

    [
        'name' => '登出',
        'action' => 'courier-logout',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code' ]
    ],  

    [
        'name' => '获得快递员信息',
        'action' => 'courier-info',
        'token' => true,
        'param' => [ 'courier_id' ],
        'response' => [ 'code', 'courier' ]
    ],  

    [
        'name' => '修改密码',
        'action' => 'courier-password-update',
        'token' => true,
        'param' => [ 'old_password', 'password' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '更新头像',
        'action' => 'courier-avatar-update',
        'token' => true,
        'param' => [ 'img(头像文件名，需调用照片上传接口上传照片后获得)' ],
        'response' => [ 'code' ]
    ],  

    [
        'name' => '信息更新',
        'action' => 'courier-info-update',
        'token' => true,
        'param' => [ 'alipay_id', 'phone' ],
        'response' => [ 'code', 'message' ]
    ],   

    [
        'name' => '抢单',
        'action' => 'waybill-compete',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '获得快递单列表',
        'action' => 'waybill-list',
        'token' => true,
        'param' => [ 'year', 'month(月份，从1开始)', 'day' ],
        'response' => [ 'code', 'waybill_list' ]
    ],  

    [
        'name' => '获得送单列表',
        'action' => 'waybill-deliver-list',
        'token' => true,
        'param' => [ 'year', 'month(月份，从1开始)', 'day' ],
        'response' => [ 'code', 'waybill_list' ]
    ],  

    [
        'name' => '获得收单列表',
        'action' => 'waybill-receive-list',
        'token' => true,
        'param' => [ 'year', 'month(月份，从1开始)', 'day' ],
        'response' => [ 'code', 'waybill_list' ]
    ], 

    [
        'name' => '获得抢单列表',
        'action' => 'waybill-compete-notify-list',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code', 'waybill_list' ]
    ],  

    [
        'name' => '从抢单列表中忽略某个快递单',
        'action' => 'waybill-compete-ignore',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code' ]
    ], 

    [
        'name' => '获得快递单详情',
        'action' => 'waybill-info',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'waybill' ]
    ],  

    [
        'name' => '更新快递单状态为派送成功',
        'action' => 'waybill-status-update-deliver-success',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '更新快递单状态为派送失败 ',
        'action' => 'waybill-status-update-deliver-fail',
        'token' => true,
        'param' => [ 'waybill_id', 'reason' ],
        'response' => [ 'code', 'message' ]
    ],                                                       

    [
        'name' => '更新快递单状态为取件成功',
        'action' => 'waybill-status-update-receive-success',
        'token' => true,
        'param' => [ 'waybill_id', 'org_waybill_id(快递公司运单号码)' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '更新快递单状态为取件失败',
        'action' => 'waybill-status-update-receive-fail',
        'token' => true,
        'param' => [ 'waybill_id', 'reason' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '更新快递单状态为派送中',
        'action' => 'waybill-status-update-delivering',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'message' ]
    ],   

    [
        'name' => '更新快递单历史状态列表',
        'action' => 'waybill-status-list',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'status_list' ]
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
        'name' => '给快递单增加拍照照片',
        'action' => 'waybill-img-save',
        'token' => true,
        'param' => [ 'waybill_id', 'img', 'longitude', 'latitude' ],
        'response' => [ 'code', 'message' ]
    ], 

    [
        'name' => '获得快递单照片列表',
        'action' => 'waybill-img-list',
        'token' => true,
        'param' => [ 'waybill_id' ],
        'response' => [ 'code', 'waybill_img_list' ]
    ],  

    [
        'name' => '快递员社区发布新的文章',
        'action' => 'post-create',
        'token' => true,
        'param' => [ 'content', 'title' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '获得快递员社区文章列表',
        'action' => 'post-list',
        'token' => true,
        'param' => [ 'limit', 'offset' ],
        'response' => [ 'code', 'post_list' ]
    ],  

    [
        'name' => '快递员社区文章新增评论',
        'action' => 'post-comment-save',
        'token' => true,
        'param' => [ 'post_id', 'content' ],
        'response' => [ 'code', 'message' ]
    ],   

    [
        'name' => '获得快递员社区文章评论列表',
        'action' => 'post-comment-list',
        'token' => true,
        'param' => [ 'post_id', 'limit', 'offset' ],
        'response' => [ 'code', 'comment_list' ]
    ],  

    [
        'name' => '快递员社区文章顶、踩等操作',
        'action' => 'post-action-save',
        'token' => true,
        'param' => [ 'post_id', 'type(1表示顶，2表示踩)' ],
        'response' => [ 'code', 'like_count', 'dislike_count' ]
    ],  

    [
        'name' => '快递员群聊发送新的即时消息',
        'action' => 'message-org-send',
        'token' => true,
        'param' => [ 'org_id', 'content', 'type(1表示文本，2表示图片，3表示声音)' ],
        'response' => [ 'code', 'message' ]
    ],  

    [
        'name' => '快递员给用户发送即时消息',
        'action' => 'message-user-send',
        'token' => true,
        'param' => [ 'user_id', 'content', 'content_type(1表示文本，2表示图片，3表示声音)' ],
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
        'name' => '获得快递公司信息',
        'action' => 'courier-org-info',
        'token' => true,
        'param' => [  ],
        'response' => [ 'code', 'org', 'root_org', 'org_member_list' ]
    ],  

    [
        'name' => '检查更新',
        'action' => 'check-update',
        'token' => true,
        'param' => [  ],
        'response' => [ 'version', 'version_number', 'url' ]
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
