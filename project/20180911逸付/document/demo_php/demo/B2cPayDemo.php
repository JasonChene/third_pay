<?php
/**
 * B2C 支付Demo
 * Date: 2017/12/29
 * Time: 12:33
 */
@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$param = array();
$param["payKey"] = $payKey;// 商户支付Key
$param["orderPrice"] = "15";// 支付金额
$param["outTradeNo"] = date("Ymdhis",time())."000002";//订单编号
$param["productType"] = "50000103";//B2C T0支付
$param["orderTime"] = date("Ymdhis",time());// 订单时间
$param["productName"] = "背包";// 商品名称
$param["orderIp"] = $orderIp;// 下单IP
$param["bankCode"] = "ABC";//银行编码
$param["bankAccountType"] = "PRIVATE_DEBIT_ACCOUNT";//PRIVATE_DEBIT_ACCOUNT 对私借记卡  PUBLIC_ACCOUNT 对公
$param["returnUrl"] = $returnUrl;// 页面通知返回url
$param["notifyUrl"] = $notifyUrl;// 后台消息通知Url
$param["remark"] = "支付备注";// 后台消息通知Url


$string=signString($param);

echo '<br>';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$b2cPayUrl);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
$data = curl_exec($ch);

echo  "<br> 返回的数据：".$data;

curl_close($ch);
