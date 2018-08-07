<?php
header("Content-type: text/html; charset=utf-8"); 
require_once("../Function/HttpClient.php");
require_once("../Function/Util.php");
//====================配置商户的宝付接口授权参数==============
$merchant_ID		= "1256060676";
$key			= "C89C2D1BCAF9CA258A34E59DD4BF4668";
$Pay_url = "https://bq.baiqianpay.com/webezf/web/?app_act=openapi/bq_pay/pay";//API提交地址
$notify_url = "http://localhost/Action/NotifyUrl.php";//页面跳转地址
$return_url = "http://localhost/Action/ReturnUrl.php";//服务器通知地址

