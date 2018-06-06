<? header("content-Type: text/html; charset=UTF-8");?>
<?php
/* *
 *功能：刷卡支付接口
 *版本：3.0
 *日期：2016-07-10
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
	

///////////////////////////  初始化接口参数  //////////////////////////////
/**
接口参数请参考智付微信支付文档，除了sign参数，其他参数都要在这里初始化
*/
	
	
	$merchant_code = "800004007888";//商户号，1111110166是测试商户号，调试时要更换商家自己的商户号

	$service_type = "weixin_micropay";

	$notify_url = "http://www.zdfmf.com/Notify_Url.jsp";		

	$interface_version ="V3.0"; 
	
	$sign_type = "RSA-S";
	
	$input_charset = "UTF-8";

	$client_ip = "192.168.1.1";
	
	$order_no = date( 'YmdHis' );	

	$order_time = date( 'Y-m-d H:i:s' );	

	$order_amount ="0.01" ;	

	$product_name = "shoes";
	
	$auth_code ="130136138697196182";

	
	//以下为可选参数	

	$return_url ="http://www.baidu.com";
	
	$redo_flag ="";
	
	$product_code = "";	

	$product_num = "2";
	
	$product_desc = "";	

	$extra_return_param = "";	
	
	$extend_param = "";
	
	$show_url ="";
	
	$device_info ="";
	
	$limit_pay ="no_credit";
	
/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
*/

	$signStr = "";
	
	$signStr = $signStr."auth_code=".$auth_code."&";
	
	$signStr = $signStr."client_ip=".$client_ip."&";
	
	if($device_info != ""){
		$signStr = $signStr."device_info=".$device_info."&";
	}
	
	if($extend_param != ""){
		$signStr = $signStr."extend_param=".$extend_param."&";
	}
	
	if($extra_return_param != ""){
		$signStr = $signStr."extra_return_param=".$extra_return_param."&";
	}
	
	$signStr = $signStr."input_charset=".$input_charset."&";
	
	$signStr = $signStr."interface_version=".$interface_version."&";

	if($limit_pay != ""){
		$signStr = $signStr."limit_pay=".$limit_pay."&";
	}
	
	$signStr = $signStr."merchant_code=".$merchant_code."&";	
	
	$signStr = $signStr."notify_url=".$notify_url."&";		
	
	$signStr = $signStr."order_amount=".$order_amount."&";		
	
	$signStr = $signStr."order_no=".$order_no."&";		
	
	$signStr = $signStr."order_time=".$order_time."&";	

	if($product_code != ""){
		$signStr = $signStr."product_code=".$product_code."&";
	}	
	
	if($product_desc != ""){
		$signStr = $signStr."product_desc=".$product_desc."&";
	}
	
	$signStr = $signStr."product_name=".$product_name."&";

	if($product_num != ""){
		$signStr = $signStr."product_num=".$product_num."&";
	}	
	
	if($redo_flag != ""){
		$signStr = $signStr."redo_flag=".$redo_flag."&";
	}	
	if($return_url != ""){
		$signStr = $signStr."return_url=".$return_url."&";
	}	
	
	$signStr = $signStr."service_type=".$service_type;
	
	if($show_url != ""){
		$signStr = $signStr."&show_url=".$show_url;
	}

		echo $signStr;
	
/////////////////////////////   RSA-S签名  /////////////////////////////////



/////////////////////////////////初始化商户私钥//////////////////////////////////////
/**
1）merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作获取商户私钥。获取商户私钥的同时，也要
获取商户公钥（merchant_public_key）并且将商户公钥上传到智付商家后台"公钥管理"（如何获取和上传请看《密钥对获取工具说明》），
不上传商户公钥会导致调试的时候报错“签名错误”。
2）demo提供的merchant_private_key是测试商户号1118004517的商户私钥，请自行获取商户私钥并且替换；
3）调用openssl_sign函数获取到参数sign的值,需要在php_ini文件里打开php_openssl插件。
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOD/K22eGec3qNQm
k9LwsjpbJDJE9JYfsTJJQGJhfWsKbcZ9UISKXZxuhSCVaf2z9/pEln5RoE7GNwOr
Yv8R00P8nRJONHNPaLcf0Y8+c6DBWGVewZKojUzn18uAEGGW5XMjLs5/OU//opRB
4ieeSmBJ4jp954XfR4Z57bjOpe/3AgMBAAECgYEArCr2K2JQxfp0aSq/8SkX6Mm3
T/QuCPZlXGprJx0coJ0RVVKtG07ZxQtZOY671VQyjEKRukVx2vWYQWmTTkVwl+U7
1fh1mmiu00Y3odNoERc02ZN0zJmrSuhbcuEv6F8kBATunB55wOZ3jlbkXD9h+KUy
ePBOkrPb+81LhJ6kZXkCQQD18nQ1U2m9laS8ROJmZ1LuecQ4maaHW3xFxHoM9sS1
YcpB3peQuXBrKa483zYADIJV2NYstc0QXMMZIXleKFFzAkEA6jF+xx4q+p/lhH8M
3rHucHmkgFce90Jh1eHTdx5czizl3LiOYZ5D7cNL8x7piJDMmzkVz8+OidXm0wf5
aT82bQJAP9TSJjjk26hn3dj+7Vbppi0CKTJvjvfGdBD/IDg3a1/a72eG7K/EJnvl
1bSUvkSA2yjwxR/V/eYlWHNgnXhXUwJBANA6h+3FfhNvXmSrjqbncAljrwdJ70eM
J29DpoFQZtYPB6Z0FmzniqB6OCqIPr7leHc/j4xBkQwvO1hBy9pvkRUCQEVOGouG
VeiXL/MuupUdbdBSV4nkYb9hrqE11gzbLu4A+OCpV8Xwdqu5SqX9Js1mQ6vQwTHu
63vyfpxxl7oN9Jw=
-----END PRIVATE KEY-----';
			
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
		
	openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	
	$sign = base64_encode($sign_info);
			
?>
<!-- 以post方式提交所有接口参数到智付支付网关https://api.zdfmf.com/gateway/api/micropay -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad="document.dinpayForm.submit();">
<form name="dinpayForm" method="post" action="https://api.zdfmf.com/gateway/api/micropay" target="_blank"><!-- 注意 非UTF-8编码的商家网站 此地址必须后接编码格式 -->
			<input type="hidden" name="merchant_code" value="<?php echo $merchant_code?>" />
			<input type="hidden" name="service_type"  value="<?php echo $service_type?>"/>
			<input type="hidden" name="notify_url"    value="<?php echo $notify_url?>">
			<input type="hidden" name="interface_version" value="<?php echo $interface_version?>"/>
			<input type="hidden" name="input_charset" value="<?php echo $input_charset?>"/>
			<input type="hidden" name="sign_type"     value="<?php echo $sign_type?>"/>
			<input type="hidden" name="sign"		  value="<?php echo $sign?>" />
			<input Type="hidden" Name="return_url"    value="<?php echo $return_url?>"/>
			<input Type="hidden" Name="client_ip"     value="<?php echo $client_ip?>"/>
			<input type="hidden" name="order_no"      value="<?php echo $order_no?>"/>
			<input type="hidden" name="order_amount"  value="<?php echo $order_amount?>"/>
			<input type="hidden" name="order_time"    value="<?php echo $order_time?>"/>
			<input type="hidden" name="redo_flag"		  value="<?php echo $redo_flag?>" />
			<input type="hidden" name="product_name"  value="<?php echo $product_name?>"/>
			<input Type="hidden" Name="product_code"  value="<?php echo $product_code?>"/>
			<input Type="hidden" Name="product_desc"  value="<?php echo $product_desc?>"/>
			<input Type="hidden" Name="product_num"   value="<?php echo $product_num?>"/>
			<input Type="hidden" Name="extend_param"  value="<?php echo $extend_param?>"/>
			<input Type="hidden" Name="extra_return_param" value="<?php echo $extra_return_param?>"/>	
			<input Type="hidden" Name="show_url"      value="<?php echo $show_url?>"/>
			<input type="hidden" name="auth_code"     value="<?php echo $auth_code?>"/>
			<input type="hidden" name="device_info"     value="<?php echo $device_info?>"/>
			<input type="hidden" name="limit_pay"     value="<?php echo $limit_pay?>"/>
			</form>
</body>
</html>
