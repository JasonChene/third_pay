<?php
$key='07a22975-bfc8-40d6-b03d-e48e28227873';
$privatekey='-----BEGIN RSA PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAMYNmB3qEoqKkGjq
th/aVHYU+BBmOF/StBsDelCMj4ZeEA+DB37ZR5iGWPqpnw2YAD0gNlS2H39a3uEU
7THLIn0D37dkiEcOAZgOX+N+AVeqshV5GN22flNM45iAlQVZVFpLet5YbvV/TqIs
8LlT61+EDQgs4nGe7QcOia3lycIlAgMBAAECgYBeeheplYKYwjbXRbfxg/Ysih43
vHuCAoJWLJeJmzPQNkjVDX1i3oWP1e7WTFoKYwsiHZ6tVF+8If4WQyRPT4a4Y05D
X7pxSKsHweg1xI/9VrUNtGvMyBDTVuza5tYUVfds2ZaUwk5toko5gbE9K5XxlCFS
bKs+zkMFAGrpXZB2AQJBAOknV9GGpnsnamN6wljfYK3yqJ/dVvYFpskbUc/Mf3YF
LL0XQ8bDIpWfT78IliOd5cI7699+cQPEnTmCxky8DoECQQDZdcDpgQRgFJWKVmN5
eighKMKHtgBi5TXbHLGadhp4Nm77o0Rq8h+Z7/KyeNKlqR76TXU/M9djhI632gqI
4emlAkBh9jXfw0OP2y/IPHwL+08TxzEyej1fgWBifygQt0uWuvXhPTUs/jA5zYYk
Lednb6Bpy+N/NBEoFCQ7Vccb1qgBAkEA2LCu979ZNcitLrlgzFa+kDRGi+b/QYy3
esx/6bPQMoPDWbraXVtBxCpHBDLDbNI2jSMMN1uYQJGBiZCbOMUtBQJAJ3CjuUXS
O/Mgyx4wKAzu/Vuqtq8eZ8ef2ohCKx4AMFOw7on5zYpjfbjq3oftqD6q8QsBfGWc
xwNxA27CWaE2Jg==
-----END RSA PRIVATE KEY-----';
$publickey='-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0Q9rwXclkLnIPK49wFhj
ScQ7WfghB1KaGxTVmyR7bxkHIxk6G8CuX0f2Q3CUlTYdm6sdJsm3yaElA+9D4eSf
9ayMjOpT6EiqijM2LKWgwwFeHS8LYq1yOZ3L/+tNqrQsDH8oRUBBunf+sQijEo+e
M2RVahQzXCAXmx45r/8HaVKCXdOJqixe9icFUr3oNjOk/zTcDSYJnMAXCyvl5NC8
Zc4W4dM6VTpel4G65imn80b+WjJXK5h5+iJY+2CsfYUy5lrXtfqPF77zrCfk3Xt6
9icNZ/rPefvrvfQk0ZI9pflNk0vzzExg/zD+5DCndNBab0UlxPwOCoVlfTiBI5zD
1wIDAQAB
-----END PUBLIC KEY-----';

require_once('common.php');
$common = new COMMON();

$timestamp = time();
$nonce =$common->RandStr(8);
$arrayData = array(
	'order_trano_in' => time(),
	'order_goods' => '测试',
	'order_price' => 100,
	'order_num' => 1,
	'order_amount' => 100,
	'order_imsi' => '',
	'order_mac' => '',
	'order_brand' => '',
	'order_version' => '',
	'order_extend' => '',
	'order_bank_code' => '',
	'order_openid' => '',
	'order_return_url' => 'http://www.baidu.com',
	'order_notify_url' => 'http://www.baidu.com'
);
	
require_once('rsa.class.php');
$Rsa = new RSA($privatekey,$publickey);

$str = $common->ParameSort($arrayData);

$signature = $Rsa->Sign($timestamp.$nonce.$common->ParameSort($arrayData));

require_once('des.class.php');
$Des = new DES(strtoupper(substr(md5($timestamp.$key.$nonce),0,8)));

$post_data = $Des->encrypt(json_encode($arrayData));

$result = $common->send_post('http://127.0.0.1/h5/PayOrder',$key,$timestamp,$nonce,$signature, $post_data);

//echo $timestamp.$nonce.$common->ParameSort($arrayData) . "<br>" . $signature
echo var_dump($result);



?>