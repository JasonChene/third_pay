<?php
//=======================卡类支付和网银支付公用配置==================
//商户ID
$merchant_id		= '1621'; //填写自己的

//通信密钥
$merchant_key		= '561adf01a6cd4c34b549c5fcafb62433';//填写自己的


//==========================卡类支付配置=============================
//支付的区域 0代表全国通用	
$restrict			= '0';


//接收下行数据的地址, 该地址必须是可以再互联网上访问的网址
$callback_url		= "http://localhost:8811/callback/card.php";   
$callback_url_muti  = "http://localhost:8811/callback/muti_card.php";

//======================网银支付配置=================================
//接收网银支付接口的地址
$bank_callback_url	= "http://localhost:8811/callback/bank.php";  


//网银支付跳转回的页面地址
$bank_hrefbackurl	= '';


?>