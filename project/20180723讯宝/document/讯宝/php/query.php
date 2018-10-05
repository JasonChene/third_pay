<?php

//商户订单号	orderid	Y	需查询的商户系统订单号
//商户ID	 parter	Y	商户id，由讯宝商务分配。
//MD5签名	sign	-	32位小写MD5签名值，GB2312编码

$orderid = 'In8P35ngGmRrYdpYCnPqooJPr6DkBJ'; //商户订单号
$parter = '1275'; //商户ID
$key = 'be8c2fadfb764e169f5a59b4315d0889'; //商户密钥
$gateWary = 'http://gateway.xunbaopay9.com/Search.aspx'; //接入URL

$params = "parter=" . $parter;
$params .= "&orderid=" . $orderid;
$P_PostKey = md5($params . $key);
$params .= $P_PostKey;

$url = "$gateWary?$params";

// 创建一个cURL资源
$ch = curl_init();
// 设置URL和相应的选项
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//执行curl
$result = curl_exec($ch);
//关闭cURL资源，并且释放系统资源
curl_close($ch);

var_dump($result);
