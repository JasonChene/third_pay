<?php
define('SRC_CODE', '');
define('SRC_KEY', '');
require_once('function.php');

$mchid = get_mchid();
$api_url = '';

//载入厂商公钥和客户的私钥
$public_key = file_get_contents("rsa_public_key.pem");
$pub_key = openssl_pkey_get_public($public_key);
$client_private_key = file_get_contents("client_rsa_private_key.pem");
$client_pri_key = openssl_pkey_get_private($client_private_key);

//余额查询参数构造
$order_info = array();
$order_info['src_code'] = SRC_CODE;
$sign = get_md5($order_info, SRC_KEY);
$str = '';
//虽然现在只有一个参数src_code，避免以后加参数，所以，用了遍历数组
foreach($order_info as $k => $v){
	$str .= $k.'='.$v.'&'; 
}
$str .= 'sign='.$sign;
//要提交接口的参数数组
$post_data = '';
$encrypt_str = '';
//按117字节分段加密，并连接加密结果,最后做base64编码
$segment = str_split($str,117);
foreach($segment as $seg){
	openssl_public_encrypt($seg, $encrypted, $pub_key);
	$encrypt_str .= $encrypted;	
}
$post_data['encrypt_data'] = base64_encode($encrypt_str);

//调用接口
$res = http($api_url, $post_data);
if($res['http_code'] == 200){
	$return_info = json_decode($res['http_data'], true);
	//按128位分段解密，并连接解密结果
	$res_arr = str_split(base64_decode($return_info['data']), 128);
	$res_str = '';
	foreach($res_arr as $v){
		openssl_private_decrypt($v, $decrypted, $client_pri_key);
		$res_str .= $decrypted;
	}
	echo '获得返回结果，并解密如下，之后逻辑由客户端实现'."\n";
	echo $res_str."\n";
}
else{
	echo '接口错误，记录日志'."\n";
}
