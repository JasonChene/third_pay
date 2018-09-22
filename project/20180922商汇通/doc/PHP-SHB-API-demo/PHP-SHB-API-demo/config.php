<?php
//=======================网银支付公用配置==================
//商户ID
$id		= '1621';

//通信密钥
$key		= '9af4f9f2bd33420bbdf623781a8fac0a';	//hc6NOTDETVQe9Lgr


$submiturl = 'http://agentpay.woyopay.com/pay.aspx';  //网关地址

//接收下行数据的地址, 该地址必须是可以再互联网上访问的网址
$callback_url		= "http://apay.woyopay.com/callback.php";   
$hrefback_url  = "http://apay.woyopay.com/hrefback.php";

?>