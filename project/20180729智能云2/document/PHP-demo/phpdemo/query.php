<?php
//查询demo，下单信息请根据实际信息填写；
include 'config.php';
header("Content-type: text/html; charset=utf-8");
$data["uid"] = $uid;//商户号
$data["token"] =  $token;//密钥
$data["url"] = "http://zny.39n6.cn/pay/select";//地址
$data["price"] = "0.01";
$data["orderid"] = "";
$data["orderuid"] = "";
$data["key"] = md5($data["uid"].$data["orderid"].$data["price"].$data["orderuid"].$data["token"]);
$ch = curl_init($data["url"]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$info = json_decode(curl_exec($ch), true);
//var_dump($info);
//验签
$check = md5($data["uid"].$info["orderid"].$info["price"].$info["realprice"].$info["orderuid"].$info["ordno"].$info["status"].$data["token"]);
if($check == $info["key"]){
	echo "验签成功";
}else{
	echo "参数有误";
}
?>