<?php
$key='07a22975-bfc8-40d6-b03d-e48e28227873';

require_once('common.php');
$common = new COMMON();

$timestamp = time();
$nonce =$common->RandStr(8);
$arrayData = array(
	'order_trano_in' => time(),
	'order_goods' => '测试',
	'order_price' => 1000,
	'order_num' => 1,
	'order_amount' => 100,
	'order_imsi' => '',
	'order_mac' => '',
	'order_ip' => '127.0.0.1',
	'order_brand' => '',
	'order_version' => '',
	'order_extend' => '',
	'order_bank_code' => '',
	'order_openid' => '',
	'order_return_url' => 'http://www.baidu.com',
	'order_notify_url' => 'http://www.baidu.com'
);
	

$str = $common->ParameSort($arrayData);

$signature = md5($timestamp.$nonce.$str);

require_once('des.class.php');
$Des = new DES(strtoupper(substr(md5($timestamp.$key.$nonce),0,8)));

$post_data = $Des->encrypt(json_encode($arrayData));

$result = $common->send_post_md5('https://127.0.0.1/h5/PayOrder',$key,$timestamp,$nonce,$signature, $post_data);
echo var_dump($result);

//echo $timestamp.$nonce.$common->ParameSort($arrayData) . "<br>" . $signature
//echo $signature ."<BR>".$timestamp.$nonce.$str


?>