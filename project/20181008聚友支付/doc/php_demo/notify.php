<?php

//商户密钥
$signkey = "xxxxxxxxx";

$mid = $_POST["mid"];
$oid = $_POST["oid"];
$amt = $_POST["amt"];
$way = $_POST["way"]; //1微信扫码
$code = $_POST["code"];
$sign = $_POST["sign"];

if(md5($mid.$oid.$amt.$way.$code.$signkey)!=$sign){
	echo "sign is error";
	exit;
}

if($code=="100"){
	//支付成功，处理业务逻辑
	
	//必须返回ok
	echo "ok";
}
else {
	//支付失败
}