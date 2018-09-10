<?php
//=======================卡类支付和网银支付公用配置==================
//联瑞商户ID
$obao_merchant_id		= '1080';

//联瑞通信密钥
$obao_merchant_key		= 'b9fc4b3d1c4a4e3b9fdc94cc4faa6e9a';


//==========================卡类支付配置=============================
//支付的区域 0代表全国通用	
$obao_restrict			= '0';


//接收联瑞下行数据的地址, 该地址必须是可以再互联网上访问的网址
$obao_callback_url		= "http://www.huikawangluo.com/php_demo/callback/card.php";   
$obao_callback_url_muti  = "http://www.huikawangluo.com/php_demo/callback/muti_card.php";

//======================网银支付配置=================================
//接收联瑞网银支付接口的地址
$obao_bank_callback_url	= "http://www.huikawangluo.com/php_demo/callback/bank.php";  


//网银支付跳转回的页面地址
$obao_bank_hrefbackurl	= '';


?>