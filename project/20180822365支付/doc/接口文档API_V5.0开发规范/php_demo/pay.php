<?php
include 'base.php';
/**
 * ---------------------参数生成页-------------------------------
 * Author:PsLove
 * Project:PayApi
 * 
 * 在您自己的服务器上生成新订单，并把计算好的订单信息传给您的前端网页。
 * 注意：
 * 1、api_key一定要在服务端计算，不要在网页中计算。
 * 2、sign只能存放在服务端，不可以以任何形式存放在网页代码中（可逆加密也不行），也不可以通过url参数方式传入网页。
 * --------------------------------------------------------------
 */

	//isset
	//判断是否使用测试页面的手动输入商户号
	$return_type = empty($_POST["return_type"]) ? 'data' : $_POST["return_type"];
	$api_code = empty($_POST["api_code"]) ? "34393032" : $_POST["api_code"];	//此处填写商户的id;
	$api_key = empty($_POST["api_key"]) ? "d57a2b05447e305a0f67ff0e5627cc86" : $_POST["api_key"];	//此处填写的密钥;			

    $price = empty($_POST["price"])?'1.00':$_POST["price"];	//从网页传入price:交易金额
    $is_type = empty($_POST["is_type"])?'wechat':$_POST["is_type"];	//is_type：支付渠道：weixin = 支付宝；alipay = 微信支付；
	$mark = "mark";	//此处填写产品名称，或充值，消费说明
	$time = time();

	$order_id = "2547884521";	
	//$order_id = order_sn(); //此处就在您服务器生成新订单，订单号不能重复提交。	
    $return_url = 'http://47.52.72.144:10001/paytest/payreturn.php';//支付成功，用户会跳转到这个地址
    $notify_url = 'http://47.52.72.144:10001/paytest/paynotify.php';//通知异步回调接收地址

	
	$signdata = array( 
		'return_type' => $return_type,		//用户订单编号ID
		'api_code' => $api_code,			//此处填写商户的id		
		'is_type' => $is_type,				//支付渠道						
		'price' => $price,					//支付金额
		'order_id' => $order_id,			//订单号							
		'time' => $time,					//支付时间
		'mark' => $mark,					//此处填写产品名称，或充值，消费说明
		'return_url' => $return_url,		//支付成功，用户会跳转到这个地址
		'notify_url' => $notify_url,		//通知异步回调接收地址
	);   	

	$returndata = array( 
		'return_type' => $return_type,		//用户订单编号ID
		'api_code' => $api_code,			//此处填写商户的id	
		'is_type' => $is_type,				//支付渠道						
		'price' => $price,					//支付金额
		'order_id' => $order_id,			//订单号							
		'time' => $time,					//支付时间
		'mark' => $mark,					//此处填写产品名称，或充值，消费说明
		'return_url' => $return_url,		//支付成功，用户会跳转到这个地址
		'notify_url' => $notify_url,		//通知异步回调接收地址
		'sign' => make_sign($signdata,$api_key),	//base.php里签名md5加密
	);    
	//
	
    echo json_return("OK",$returndata,1);

?>