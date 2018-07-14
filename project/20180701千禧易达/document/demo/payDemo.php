<?php

header("Content-type: text/html; charset=utf-8");
//  $jsonStr = file_get_contents("php://input");  回调时获取报文的方法，返回json格式数据
// notify_method($jsonStr); // 回调处理

createOrder2701();

function notify_method($str)
{
	$web_public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0tU8imsKRK3J5nCT1PQu
ZujwhykJP6TOZZAZc6qSW0CSqFooQaO6XPQl2lmRMknfR83SYPjphDpUaqlKxswY
Y3DO/D9oj9lRATMwou3HKLUJ6lsQ+VeCDx01lq0OaGHRaTb2R3r3wc+IbleQnphS
T8Au7/ZDfEYnnK9qCSMcqBZhDAamyBQa5ykRqX/BRsOL9mwk0v39mZewAt4tsvae
lWJu6E+KY5guZQtib4q8kzEzT3amO0WOV5/c5SdG0MBkc+XE9Wcb6JOjocdG9yCN
nVVh0NEjrGB7qs3bq0zvQ89dajZIh8yHPIQlnCKwIB3XCVKvR2/RjbhHSeYdmHuX
hQIDAQAB
-----END PUBLIC KEY-----'; // 平台公钥
	$resp = json_decode($str, true);
	$web_public_key = openssl_get_publickey($web_public_key);
	$flag = openssl_verify(getSignStr($resp), base64_decode($resp['sign']), $web_public_key, OPENSSL_ALGO_MD5);
	if ($flag == true && $resp['state'] == '00') {
		echo '<br/>回调数据验签成功<br/>';
		if (intval($resp['pay_status']) == 1) {
			echo '<br/>订单支付成功<br/>';
		}
		if (intval($resp['opay_status']) == 1) {
			echo '<br/>订单提现成功<br/>';
		}
		echo 'success';
	} else {
		echo '<br/>回调数据验签失败<br/>';
	}
}
/*创建支付订单*/
function createOrder2701()
{
	$merchant_private_key = '-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCdWnNKBN2U1auP
F2FXRLfKGuMYOUObxrZsWtVr//3Z5/ADcCC6T4q9JHWFubTe/1YYGKVVEz5AseQl
S6A4AIUGsyRRiuAN6VvxVOmmg0pcvm8cUaMd2viRCnb2JtaRrjPpeoKxNu+9bSot
wFfOx6HeImKF0uFr7Sfhx6xA/P2s5pVLfkUduc29fOKAgbR6XP49mfvcr12EwIr3
8H2qNPyAHgrLl/6cfcYYd5xBveDPh+9h2JMqhHwJzSTbHWA/rLDLHsnhS0OhTili
B9pyYCra5t0HL/17WMRgTdnxyQGhbA9d3/rLTUwIulq/ZquJoiGpMDWjs2K6WR3r
HNGY+qfpAgMBAAECggEAZ7aCXrsuQTdESKAkbJzCdteZ7xFvWnFzM5/7I6Aq9UFG
lT2GlMZwr5IkU+u/J2wslt1Hu/dfBM07jsl15POSuoPA4G4kl4bELyDEkBfhH5f1
LDkyxi7Zvt+i4UNgEc08MhupoJyRD82wC0/HkGdMbVlEjugb5EMAEnTFOGCH4zlf
VH6BxwbUwA1F2gQ2GktcYke6wKUFRlgSZA19buhk+8/IMmhGjgKwop67lo3r++Gs
LJ+I/K1UK5UBoGfXMtLm3cSWDfcm7us3yEOrjQpH7IdlmV0+lbwDTZOFMAnJrQfJ
qXi4dwSsSCu7SMHASBYmvyPfZ/ilY7oWjRb1xY1x8QKBgQDQNqFccSRpKHPadMlk
J+2nirWbQCcF9F+IW2MnU9KHoU77Dibz++dCNvXYanRuzLPdpfbLA8T0bfFCTvNT
1wRNTiIjFBpSHH+UAFjJqZr4tlwzVIdTqbx7t0joBxC0NFd7Yb6ccDYBf5QvJ+wB
R+Xdl6BB7G3L54zNMr/4sQazkwKBgQDBd5ZMm/A8N7eE7sNJ9DgQeOISYTjGD5m7
63hA//Eqq28BUFge9BKfofqUtL0LQk2PycyONn+1WxZiSkzyJo2ja0gn6ZVtatAD
eY8WX+iDDtrS2MjnV3ZZJm403W1Pftnj17Q3E6J4/opm7Lgs++Inozgzjot3UOql
Nn4pK9PcEwKBgDEgYqw0CdpB8CvgGFBoV1uLj9PkrBBsm0nJ/jgeP/M+bSsxKKGy
ktr9qr34SCaIZ/vpF7TI2+SsOBtkE2d5uQsgX0+Vg6xSCwv5lPln6ie6p0B5NkDY
MJ+kHDCa0icinm1/H4E7vJJX7re9nKKkuyiwiOBlD3bn2EHmMoNUCXe/AoGAEmiq
qId+CHzUvZVqh7LxUr/t4wnVOSNq4XK6cpToAcNmQJ3AhNF8pCvpiBTamCOq9a+i
AzY0WLFeI+QmBjSc7ZvbtdCII20ydeIvN1XQ7geP0thF5Z1w6XK6sdUP/ax4VzHD
OCpqH1E5IioMLFubXWIuitlZc/UDHs1cm9ZLxnkCgYABUBB7C4FtJeI9X1+srShY
vgRjfeNvylLxLYnMKsqnlVCrq2vEIMXBRg69RHKUyGCw5VglVjl+/vWMSne6x9Qn
8D+WZ1dYuEvr0oB1oOlaNGD/EK6ioe5BNT6wjzKlajflVxsjmoetVA15pfcHi3Fw
qDbyLcsykzc7+K9INTMj+g==
-----END PRIVATE KEY-----'; // 商户私钥
	$web_public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0tU8imsKRK3J5nCT1PQu
ZujwhykJP6TOZZAZc6qSW0CSqFooQaO6XPQl2lmRMknfR83SYPjphDpUaqlKxswY
Y3DO/D9oj9lRATMwou3HKLUJ6lsQ+VeCDx01lq0OaGHRaTb2R3r3wc+IbleQnphS
T8Au7/ZDfEYnnK9qCSMcqBZhDAamyBQa5ykRqX/BRsOL9mwk0v39mZewAt4tsvae
lWJu6E+KY5guZQtib4q8kzEzT3amO0WOV5/c5SdG0MBkc+XE9Wcb6JOjocdG9yCN
nVVh0NEjrGB7qs3bq0zvQ89dajZIh8yHPIQlnCKwIB3XCVKvR2/RjbhHSeYdmHuX
hQIDAQAB
-----END PUBLIC KEY-----'; // 平台公钥
	$seller_id = '561713165';
	$order_type = '2704';
	$out_trade_no = 'C234289349898' . time();
	$pay_body = '支付订单描述';
	$total_fee = '9120';
	$spbill_create_ip = $_SERVER['REMOTE_ADDR'];
	$spbill_times = time();
	$noncestr = 'cnl' . time();
	$remark = '支付订单备注';

	$parameter = array(
		'seller_id' => $seller_id,
		'order_type' => $order_type,
		'out_trade_no' => $out_trade_no,
		'pay_body' => $pay_body,
		'total_fee' => $total_fee,
		'notify_url' => 'http://www.baidu.com/',
		'return_url' => 'http://www.baidu.com/',
		'spbill_create_ip' => $spbill_create_ip,
		'spbill_times' => $spbill_times,
		'noncestr' => $noncestr,
		'remark' => $remark

	);
	$merchant_private_key = openssl_get_privatekey($merchant_private_key);
	openssl_sign(getSignStr($parameter), $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
	$sign = base64_encode($sign_info);
	$parameter['sign'] = $sign;
	$resp = http_poststr("http://api.qianxiyida.com/ecpay/xbdo", base64_encode(json_encode($parameter)));
	var_dump($resp);
	$resp = json_decode($resp, true);
	$web_public_key = openssl_get_publickey($web_public_key);
	if (!empty($resp['pay_url'])) {
		header("Location: " . $resp['pay_url']);
		exit;
	}
}

function queryOrder()
{
	$merchant_private_key = '-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCdWnNKBN2U1auP
F2FXRLfKGuMYOUObxrZsWtVr//3Z5/ADcCC6T4q9JHWFubTe/1YYGKVVEz5AseQl
S6A4AIUGsyRRiuAN6VvxVOmmg0pcvm8cUaMd2viRCnb2JtaRrjPpeoKxNu+9bSot
wFfOx6HeImKF0uFr7Sfhx6xA/P2s5pVLfkUduc29fOKAgbR6XP49mfvcr12EwIr3
8H2qNPyAHgrLl/6cfcYYd5xBveDPh+9h2JMqhHwJzSTbHWA/rLDLHsnhS0OhTili
B9pyYCra5t0HL/17WMRgTdnxyQGhbA9d3/rLTUwIulq/ZquJoiGpMDWjs2K6WR3r
HNGY+qfpAgMBAAECggEAZ7aCXrsuQTdESKAkbJzCdteZ7xFvWnFzM5/7I6Aq9UFG
lT2GlMZwr5IkU+u/J2wslt1Hu/dfBM07jsl15POSuoPA4G4kl4bELyDEkBfhH5f1
LDkyxi7Zvt+i4UNgEc08MhupoJyRD82wC0/HkGdMbVlEjugb5EMAEnTFOGCH4zlf
VH6BxwbUwA1F2gQ2GktcYke6wKUFRlgSZA19buhk+8/IMmhGjgKwop67lo3r++Gs
LJ+I/K1UK5UBoGfXMtLm3cSWDfcm7us3yEOrjQpH7IdlmV0+lbwDTZOFMAnJrQfJ
qXi4dwSsSCu7SMHASBYmvyPfZ/ilY7oWjRb1xY1x8QKBgQDQNqFccSRpKHPadMlk
J+2nirWbQCcF9F+IW2MnU9KHoU77Dibz++dCNvXYanRuzLPdpfbLA8T0bfFCTvNT
1wRNTiIjFBpSHH+UAFjJqZr4tlwzVIdTqbx7t0joBxC0NFd7Yb6ccDYBf5QvJ+wB
R+Xdl6BB7G3L54zNMr/4sQazkwKBgQDBd5ZMm/A8N7eE7sNJ9DgQeOISYTjGD5m7
63hA//Eqq28BUFge9BKfofqUtL0LQk2PycyONn+1WxZiSkzyJo2ja0gn6ZVtatAD
eY8WX+iDDtrS2MjnV3ZZJm403W1Pftnj17Q3E6J4/opm7Lgs++Inozgzjot3UOql
Nn4pK9PcEwKBgDEgYqw0CdpB8CvgGFBoV1uLj9PkrBBsm0nJ/jgeP/M+bSsxKKGy
ktr9qr34SCaIZ/vpF7TI2+SsOBtkE2d5uQsgX0+Vg6xSCwv5lPln6ie6p0B5NkDY
MJ+kHDCa0icinm1/H4E7vJJX7re9nKKkuyiwiOBlD3bn2EHmMoNUCXe/AoGAEmiq
qId+CHzUvZVqh7LxUr/t4wnVOSNq4XK6cpToAcNmQJ3AhNF8pCvpiBTamCOq9a+i
AzY0WLFeI+QmBjSc7ZvbtdCII20ydeIvN1XQ7geP0thF5Z1w6XK6sdUP/ax4VzHD
OCpqH1E5IioMLFubXWIuitlZc/UDHs1cm9ZLxnkCgYABUBB7C4FtJeI9X1+srShY
vgRjfeNvylLxLYnMKsqnlVCrq2vEIMXBRg69RHKUyGCw5VglVjl+/vWMSne6x9Qn
8D+WZ1dYuEvr0oB1oOlaNGD/EK6ioe5BNT6wjzKlajflVxsjmoetVA15pfcHi3Fw
qDbyLcsykzc7+K9INTMj+g==
-----END PRIVATE KEY-----'; // 商户私钥
	$web_public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAs8H7yybUOc/QVHxxhKee
TV9zs5EkRGDDZM5ynwbEyDb5VBAmaQ2QXnHMDFXrCntzadofGNKQYVhq5wBJAtQk
7ExfWIFm7h/WteqTAmMTSdcF9MuKEUfFM84WW1zcxoIYIe/EfHD645WWw4j7so/5
xEsa0KMA6L1+f2YuQVmRcmNRsihbxvm5ZDYUt9uvn9cEi6+Pt7V8vanvyr8RU7OZ
UTzBN5CotZfSyx044GtsFBgj3YPq3Fr4usZleJXU+RlHdp1st0liozmpn/JgOUdw
udj6elzkuqQR5enWbeIa5rzdsmD1cj6yG2NcM2RYvNwC/jo8VmiVRtzKV6ul9Uar
KwIDAQAB
-----END PUBLIC KEY-----'; // 平台公钥
	$seller_id = '561713165';
	$parameter = array(
		'seller_id' => $seller_id,
		'order_type' => '1010',
		'out_trade_no' => 'NqV5JufvuKsdR7rzb5I5EJtHVLHGR'
	);
	$merchant_private_key = openssl_get_privatekey($merchant_private_key);
	openssl_sign(getSignStr($parameter), $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
	$parameter['sign'] = base64_encode($sign_info);
	echo json_encode($parameter);
	$resp = http_poststr("http://api.qianxiyida.com/ecpay/xbdo", base64_encode(json_encode($parameter)));
	echo decodeUnicode(json_encode($resp));
}


function formatBizQueryParaMap($paraMap)
{
	$buff = "";
	ksort($paraMap);
	foreach ($paraMap as $k => $v) {
		if ($v != null && $v != '') {
			$buff .= $k . "=" . $v . "&";
		}
	}
	$reqPar;
	if (strlen($buff) > 0) {
		$reqPar = substr($buff, 0, strlen($buff) - 1);
	}
	return urlencode($reqPar);
}

/**
 * 	作用：生成签名
 */
function getSignStr($Obj)
{
	foreach ($Obj as $k => $v) {
		if ($v != '' && $k != 'sign') {
			$Parameters[$k] = $v;
		}
	}
	ksort($Parameters);
	return urldecode(formatBizQueryParaMap($Parameters));
}

function array_remove($data, $key)
{
	if (!array_key_exists($key, $data)) {
		return $data;
	}
	$keys = array_keys($data);
	$index = array_search($key, $keys);
	if ($index !== false) {
		array_splice($data, $index, 1);
	}
	return $data;
}
function http_poststr($url, $data_string)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charset=utf-8',
		'Content-Length: ' . strlen($data_string)
	));
	ob_start();
	curl_exec($ch);
	$return_content = ob_get_contents();
	ob_end_clean();

	$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	return $return_content;
}
function decodeUnicode($str)
{
	return preg_replace_callback(
		'/\\\\u([0-9a-f]{4})/i',
		create_function(
			'$matches',
			'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
		),
		$str
	);
}

?>