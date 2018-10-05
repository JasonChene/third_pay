<?php
header("Content-type: text/html; charset=utf-8");

function rand_str($length = 30) {
	//生成随机字符串

	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$str = '';
	for ($i = 0; $i < $length; $i++) {
		$str .= $chars[mt_rand(0, strlen($chars) - 1)];
	}
	return $str;
}

$parter = '1275'; //商户ID
$payType = '8012'; //银行类型
$value = '10'; //交易金额(注意支付金额限制)
$orderid = rand_str(); //商户订单号(订单号不可重复)
$callbackurl = 'http://ckkz9.com/xbpay/callback.php'; //下行异步通知地址
//$hrefbackurl='';                                 //下行同步通知地址(非必填参数)
$key = 'be8c2fadfb764e169f5a59b4315d0889'; //商户密钥
$m_id = '99'; //备注信息
$gateWary = 'http://gateway.xunbaopay9.com/chargebank.aspx'; //接入URL

$orderid_file = fopen('orderid.txt', 'a');
fwrite($orderid_file, $orderid . "\n");
fclose($orderid_file);

if ($key != '') {

	//开始组织支付请求
	$params = "parter=" . $parter;
	$params .= "&type=" . $payType;
	$params .= "&value=" . $value;
	$params .= "&orderid=" . $orderid;
	$params .= "&callbackurl=" . $callbackurl;
	$P_PostKey = md5($params . $key);
	$params .= "&attach=" . $m_id;
	$params .= "&sign=" . $P_PostKey;

	//开始发起
	header("location:$gateWary?$params");

}