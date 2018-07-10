<?php
//=======================卡类支付和网银支付公用配置==================
//新云支付商户ID
$aiyang_merchant_id		= '1630';

//新云支付通信密钥
$aiyang_merchant_key		= 'b9fc4b3d1c4a4e3b9fdc94cc4faa6e9a';


//==========================卡类支付配置=============================
//支付的区域 0代表全国通用	
$aiyang_restrict			= '0';


//接收新云支付下行数据的地址, 该地址必须是可以再互联网上访问的网址
$aiyang_callback_url		= "http://www.xfy78988.com/php_demo/callback/card.php";   
$aiyang_callback_url_muti  = "http://www.xfy78988.com/php_demo/callback/muti_card.php";

//======================网银支付配置=================================
//接收新云支付网银支付接口的地址
$aiyang_bank_callback_url	= "http://www.xfy78988.com/php_demo/callback/bank.php";  


//网银支付跳转回的页面地址
$aiyang_bank_hrefbackurl	= '';


?>