<?php
error_reporting(0);
header('Content-Type:text/html;charset=GB2312');
include_once("../config/pay_config.php");
include_once("../lib/class.lianruipay.php");
$lianruipay = new ekapay();
$lianruipay->parter 		= $obao_merchant_id;		//商家Id
$lianruipay->key 			= $obao_merchant_key;	//商家密钥

$result	= $lianruipay->search($_POST['order_id']);

$data = '{"success": "'.$result.'","message": "'. $lianruipay->message .'"}';
die($data);
?>