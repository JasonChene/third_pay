<? header("content-Type: text/html; charset=UTF-8");?>
<?php
	
/* *
 *功能：单笔订单查询接口
 *版本：3.0
 *日期：2016-07-10
 *说明：
 *目前只能查询距离下单时间12小时以内的
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
	

///////////////////////////  初始化接口参数  //////////////////////////////
/**
接口参数请参考智付微信支付文档，除了sign参数，其他参数都要在这里初始化
*/
		
    $merchant_code = "1111110166";//商户号，1111110166是测试商户号，调试时要更换商家自己的商户号
	
	$interface_version = "V3.0";
	
	$sign_type = "RSA-S";	
	
	$service_type ="single_trade_query";	
	
	$order_no = "20160518095201";	
	
	$trade_no = "";	
	
/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
*/

	$signStr = "";
		
	$signStr = $signStr."interface_version=".$interface_version."&";	
	
	$signStr = $signStr."merchant_code=".$merchant_code."&";	
			
	$signStr = $signStr."order_no=".$order_no."&";
	
	
	$signStr = $signStr."service_type=".$service_type;	
			
	if($trade_no != ""){	
			
			$signStr = $signStr."&trade_no=".$trade_no;	
	}
	
/////////////////////////////   RSA-S签名  /////////////////////////////////
/**
1）merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作获取商户私钥。获取商户私钥的同时，也要
   获取商户公钥（merchant_public_key）并且将商户公钥上传到智付商家后台"公钥管理"（如何获取和上传请看《密钥对获取工具说明》），
   不上传商户公钥会导致调试的时候报错“签名错误”。
2）demo提供的merchant_private_key是测试商户号1111110166的商户私钥，请自行获取商户私钥并且替换；
3）调用openssl_sign函数获取到参数sign的值,需要在php_ini文件里打开php_openssl插件
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLY
PJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7
n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtW
g6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuu
u/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3Bot
UdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqg
XppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiS
QC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJ
xJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/T
AJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z
4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvG
T9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiy
yImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMC
I6gGtaqkzl527tw=
-----END PRIVATE KEY-----';
			
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
	openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	
	$sign = base64_encode($sign_info);
	
	
	
?>
<!-- 以post方式提交所有接口参数到智付查询网关https://query.yuanruic.com/query -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body onLoad="javascript:document.getElementById('queryForm').submit();">
		<form  id="queryForm" action="https://query.yuanruic.com/query" method="post"  target="_self">
			<input type="hidden" name="interface_version" value="<?php echo $interface_version?>" />
			<input type="hidden" name="service_type" value="<?php echo $service_type?>" />
			<input type="hidden" name="merchant_code" value="<?php echo $merchant_code?>" />
			<input type="hidden" name="sign_type" value="<?php echo $sign_type?>" />
			<input type="hidden" name="sign" value="<?php echo $sign?>" />
			<input type="hidden" name="order_no" value="<?php echo $order_no?>" />
			<input type="hidden" name="trade_no" value="<?php echo $trade_no?>" />
		</form>
	</body>
</html>
