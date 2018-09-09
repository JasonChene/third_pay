<?php
include "./getSign.php";
include "./getData.php";
$url = "";
$ch = curl_init($url);
$timeout = 6000;
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER,0 );
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

$merKey = "";
$data = array(
	'merAccount' => '',//商户标识
	'merNo' => '',//商户编号
	'time' => '1513579815',//时间戳
	'orderId' => 'OR_00000011231',//订单号
	'amount' => '1000',//交易金额
	'productType' => '01',//商品编号
	'product' => '手机',//商品
	'productDesc' => 'iphone',//商品描述
	'userType' => '0',//固定
	'payWay' => 'UNIONPAY', //参考文档中的支付方式
	'payType' => 'SCANPAY_UNIONPAY',//参考文档中的支付类型
	'userIp' => '192.168.0.1',
	'notifyUrl' => 'http://xxxxx.com',//商户异步通知地址
	'returnUrl' => 'http://xxxxx.com' //页面回调地址
	
);
$data['sign'] = getSign($data,$merKey);
$encode_data = getData($data,$merKey);
$post_data = array(
	'merAccount' => '',//商户标识
	'data' => $encode_data
);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
 $ret = curl_exec($ch);
  curl_close($ch);
  echo $ret;
?>