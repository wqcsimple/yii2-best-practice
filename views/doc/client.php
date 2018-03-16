<?php
use yii\helpers\Html;
use app\assets\AppAsset;

$host = 'http://121.40.200.126';
?>
<style>

* { font-family: Consolas; }
* { font-family: Courier New; }

td.name { width: 200px; }
</style>

<blockquote>
<p><?= $host ?></p>
<p>请求方法: POST</p>
<p>基本参数: token(无token传空值), client(客户端类型，1 => iOS，2 => Android), version(版本)</p>
<p>返回数据格式: json，必包含code，code为0表示请求成功，code不为0表示请求失败</p>
<p>错误返回格式: { 'code': 1, 'message': 'some error' }</p>
</blockquote>
<div class="clear-20"></div>



<h4>用户登录</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-login</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">phone, password</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, user</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>用户注册</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-register</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">phone, password</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>



<h4>用户注销</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-logout</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class=""></td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>用户修改密码</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-password-update</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">old_password, password</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>用户重设密码-非登录</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-password-reset</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">phone, password, code</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>用户基本信息修改</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-basic-info-update</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">name, email, gender, birthday</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>用户头像修改</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-avatar-update</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">img</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>用户手机号修改</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-phone-update</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">phone</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>发送手机验证码</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-phone-verification-code-send</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">phone</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>检查手机验证码</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-phone-verification-code-check</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">phone, code</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, ok</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获得用户地址列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-address-list</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class=""></td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, address_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>保存或者新增用户发件或收件地址</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-address-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">address_id, type, name, province, city, county, address, zip, phone, telephone</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>删除用户收件地址</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-address-delete</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">address_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>设定某个发件地址为默认发件地址</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-address-set-as-default</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">address_id, type</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>创建快递单</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">
            from_customer_name, from_customer_phone, from_customer_province, from_customer_city, from_customer_county, from_customer_address, from_customer_longitude, from_customer_latitude,<br />
            to_customer_name, to_customer_phone, to_customer_province, to_customer_city, to_customer_county, to_customer_address, to_customer_longitude, to_customer_latitude,<br />
            content, comment, length, width, height, weight,<br />
            insure, insure_declared_value, insure_rate, insure_charge,<br />
            freight, bonus, charge<br />
        </td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, waybill_id, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>呼叫单个快递员</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/call-courier</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, courier_id, longitude, latitude, province, city, county, address</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>呼叫附近的快递员，10KM内</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/call-nearby-courier</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, courier_id, longitude, latitude, province, city, county, address</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获取附近快递员列表，10KM内</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-list-nearby</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">longitude, latitude</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, courier_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获得快递公司列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/express-org-list</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class=""></td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, express_org_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>按照距离搜索快递员</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-list-search-by-distance</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">longitude, latitude, distance</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, courier_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>按照快递公司搜索快递员</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-list-search-by-express-org</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">longitude, latitude, org_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, courier_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>按好评搜索快递员</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-list-search-by-rate</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">longitude, latitude</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, courier_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获得快递员详细信息</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-info</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">courier_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, courier</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获得快递员位置信息</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-location</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">courier_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, latitude, longitude</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>分页查看快递员评价列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-rate-list</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">courier_id, offset, length</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, rate_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>评价某次快递配送或者取件</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-rate-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, type, courier_id, attitude, speed, content</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>快递单列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-list</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class=""></td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, deliver_waybill_list, receive_waybill_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>快递单详情</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-info</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, waybill</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>快递单照片列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-img-list</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, waybill_img_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>已取件状态更新</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-status-update-receive-success</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>取消快件，注意，只能在取件成功状态之前进行此操作</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-status-update-cancel</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>取消快件，注意，只能在取件成功状态之前进行此操作</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-status-update-deliver-success</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>投诉某次快递服务</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-complain-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>修改代理收件人</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-to-customer-agent-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, name, phone, province, city, county, address, longitude, latitude</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>修改快递员收件小费</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-bonus-receive-update</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, bonus</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>修改快递员派件小费</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-bonus-deliver-update</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, bonus</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获得常用发件人列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-address-list-from</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, bonus</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, address_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>获得常用收件人列表</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/user-address-list-to</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, bonus</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, address_list</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>给快递员发送即时消息</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/courier-message-send</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, bonus</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>快递单拍照上传照片保存</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/waybill-img-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id, img</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


<h4>评价建议保存</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/uapi/feedback-save</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">content</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
</table>
<div class="clear-20"></div>


