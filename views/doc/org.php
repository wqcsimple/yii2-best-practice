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
<p>基本参数: 分配给快递公司的token</p>
<p>返回数据格式: json，必包含code，code为0表示请求成功，code不为0表示请求失败</p>
<p>错误返回格式: { 'code': 1, 'message': 'some error' }</p>
</blockquote>
<div class="clear-20"></div>



<h4>导入快递单</h4>
<table class="table table-condensed">
    <tr class="">
        <td class="name">action</td>
        <td class="green bold">/oapi/waybill-import</td>
    </tr>
    <tr>
        <td class="name">param</td>
        <td class="">waybill_id(快递单单号),
            from_customer_name(发件人名称), from_customer_phone(发件人手机号), from_customer_province(发件人所在省), from_customer_city(发件人所在市), from_customer_county(发件人所在区/县), from_customer_address(发件人详细地址),
            to_customer_name(收件人名称), to_customer_phone(收件人手机号), to_customer_province(收件人所在省), to_customer_city(收件人所在市), to_customer_county(收件人所在区/县), to_customer_address(收件人详细地址),
            content(快件内容), comment(快件备注), length(快件长度，单位cm), width(快件宽度，单位cm), height(快件高度，单位cm), weight(快件重量，单位KG)</td>
    </tr>
    <tr>
        <td class="name">response</td>
        <td>code, message</td>
    </tr>
    <tr>
        <td class="name">错误号</td>
        <td>
            <div>1 => 参数不完整</div>
            <div>6 => 参数错误，省市区县填写错误</div>
            <div>7 => 保存失败</div>
            <div>9 => 快件已存在</div>
            <div>18 => 地址解析错误</div>
        </td>
    </tr>
</table>
<div class="clear-20"></div>




