<?php
header("Content-type:text/html; charset=UTF8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");


$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');


if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}


//獲取第三方的资料
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_postUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $_REQUEST['pay_type'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}


//參數設定
$merchant_code = $pay_mid;//商戶id

$bankname = $pay_type . "->QQ钱包在线充值";
$payT = $pay_type . "_qq";
$service_type = "tenpay_scan";//QQ錢包掃碼
if (_is_mobile()) {
	$service_type = "qq_h5api";//QQ手機錢包掃碼
}

$notify_url = $merchant_url;
$interface_version = "V3.1";
$client_ip = getClientIp();
$sign_type = "RSA-S";
$order_no = date('YmdHis');
$order_time = date('Y-m-d H:i:s');
$order_amount = number_format($_REQUEST['MOAmount'], 2, '.', '');
$product_name = "testpay";
$merchant_private_key = $pay_mkey;
$product_code = "";
$product_num = "";
$product_desc = "";
$extra_return_param = "";
$extend_param = "";


// 確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $order_amount, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */

$signStr = "";

$signStr = $signStr . "client_ip=" . $client_ip . "&";

if ($extend_param != "") {
	$signStr = $signStr . "extend_param=" . $extend_param . "&";
}

if ($extra_return_param != "") {
	$signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
}

$signStr = $signStr . "interface_version=" . $interface_version . "&";

$signStr = $signStr . "merchant_code=" . $merchant_code . "&";

$signStr = $signStr . "notify_url=" . $notify_url . "&";

$signStr = $signStr . "order_amount=" . $order_amount . "&";

$signStr = $signStr . "order_no=" . $order_no . "&";

$signStr = $signStr . "order_time=" . $order_time . "&";

if ($product_code != "") {
	$signStr = $signStr . "product_code=" . $product_code . "&";
}

if ($product_desc != "") {
	$signStr = $signStr . "product_desc=" . $product_desc . "&";
}

$signStr = $signStr . "product_name=" . $product_name . "&";

if ($product_num != "") {
	$signStr = $signStr . "product_num=" . $product_num . "&";
}

$signStr = $signStr . "service_type=" . $service_type;


/////////////////////////////   RSA-S签名  /////////////////////////////////



/////////////////////////////////初始化商户私钥//////////////////////////////////////


$merchant_private_key = openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);

/////////////////////////  提交参数到得宝扫码支付网关  ////////////////////////

/**
curl方法提交支付参数到得宝扫码网关https://api.yuanruic.com/gateway/api/h5apipay，并且获取返回值
 */


$postdata = array(
	'extend_param' => $extend_param,
	'extra_return_param' => $extra_return_param,
	'product_code' => $product_code,
	'product_desc' => $product_desc,
	'product_num' => $product_num,
	'merchant_code' => $merchant_code,
	'service_type' => $service_type,
	'notify_url' => $notify_url,
	'interface_version' => $interface_version,
	'sign_type' => $sign_type,
	'order_no' => $order_no,
	'client_ip' => $client_ip,
	'sign' => $sign,
	'order_time' => $order_time,
	'order_amount' => $order_amount,
	'product_name' => $product_name
);

$ch = curl_init();
if (_is_mobile()) {
	curl_setopt($ch, CURLOPT_URL, "https://api.yuanruic.com/gateway/api/h5apipay");
} else {
	curl_setopt($ch, CURLOPT_URL, "https://api.yuanruic.com/gateway/api/scanpay");
}
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$xml = (array)simplexml_load_string($response) or die("Error: Cannot create object");
$array = json_decode(json_encode($xml), 1);

echo '<pre>';
echo ('<br> postdata = <br>');
var_dump($postdata);
echo ('<br> signStr = <br>');
echo ($signStr);
echo ('<br><br> array = <br>');
var_dump($array);
echo '</pre>';

// exit;

if ($array['resp_code'] == 'SUCCESS') {
	if (_is_mobile()) {
		header("location:" . $array['response']['payURL']);
	} else {
		header("location:" . '../qrcode/qrcode.php?type=' . 'qq' . '&code=' . $array['response']['qrcode']);
	}
} else {
	echo $array['response']['resp_code'] . $array['response']['resp_desc'] . $array['response']['error_code'];
}
?>
