<?php
/**
 * 扫码支付
 * User: huang
 * Date: 2017/12/29
 * Time: 13:18
 */

@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$param = array();
$param["payKey"] = $payKey;// 商户支付Key
$param["orderPrice"] = "15";// 支付金额
$param["outTradeNo"] = date("Ymdhis",time())."000002";//订单编号
$param["subPayKey"] = $subPayKey;
$param["productType"] = "70000103";//支付方式
$param["orderTime"] = date("Ymdhis",time());// 订单时间
$param["productName"] = "背包";// 商品名称
$param["orderIp"] = $orderIp;// 下单IP
$param["returnUrl"] = $returnUrl;// 页面通知返回url
$param["notifyUrl"] = $notifyUrl;// 后台消息通知Url
$param["remark"] = "支付备注";// 后台消息通知Url


$string=signString($param);

echo '<br>';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$scanPayUrl);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
$data = curl_exec($ch);

echo  "<br> 返回的数据：".$data;
curl_close($ch);