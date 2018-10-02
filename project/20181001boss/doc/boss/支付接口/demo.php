<?php

$params = array(
	"appid" => "a1",//商户apid
	"amount" => "b2", //价格 分
	"itemname" => "商品名",//商品名
	"ordersn" => "c2",//商户订单号,必须唯一,必须appid_ 开头
	"orderdesc" => "订单描述",//订单描述
	"notifyurl" => "http://api.asd.cn/appdemo/adc",//后端异步支付回调地址，改成app自己的通知地址
);

krsort($params);

$param = "";
foreach ($params as $x => $x_value) {
	$param = $param . $x_value;
};

$name = iconv('UTF-8', 'GBK', $param);

$key = "f4ab2be7f1f14b3abf8c8bde4d826690";

$utf8Str = mb_convert_encoding(strtoupper(md5($name)), "UTF-8");
$sign = strtoupper(hash_hmac("sha1", $utf8Str, $key));
//print_r($sign);
$params["sign"] = $sign; 
//print_r("=".implode("|", $params));
$url = "http://tapi.kkww502.com/pay/payment";

$postdata = http_build_query($params);

$opts = array('http' => array(
	'method' => 'POST',
	'header' => 'Content-type: application/x-www-form-urlencoded',
	'content' => $postdata
));

$context = stream_context_create($opts);

$result = file_get_contents($url, false, $context);

print_r($result);
?>