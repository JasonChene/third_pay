<?php
header("Content-type:text/html; charset=UTF8");
include_once("../../../database/mysql.config.php");
// include_once("../../../database/mysql.php");
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
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
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

$bank_code = $_REQUEST['bank_code'];
$bankname = $pay_type . "->网银在线充值";
$payT = $pay_type . "_wy";
$service_type = "direct_pay";
$scan = 'wy';
$interface_version = "V3.0";
if (strstr($_REQUEST['pay_type'], "银联钱包")) {
	$bank_code = "WAP_UNION";
	$bankname = $pay_type . "->银联钱包在线充值";
	$payT = $pay_type . "_yl";
	$service_type = "direct_pay";
	$scan = 'yl';
	$interface_version = "V3.0";
}

$notify_url = $merchant_url;
$client_ip = getClientIp();
$sign_type = "RSA-S";
$order_no = date('YmdHis');
$input_charset = "UTF-8";
$order_time = date('Y-m-d H:i:s');
$order_amount = number_format($_REQUEST['MOAmount'], 2, '.', '');
$product_name = "testpay";
$merchant_private_key = $pay_mkey;


// 確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $order_amount, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

//以下参数为可选参数，如有需要，可参考文档设定参数值

$return_url = "";

$pay_type = "";
if ($scan == 'yl') {
	$pay_type = "b2cwap";
}
$redo_flag = "";

$product_code = "";

$product_desc = "";

$product_num = "";

$show_url = "";

$extend_param = "";

$extra_return_param = "";




/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */

$signStr = "";

if ($bank_code != "") {
	$signStr = $signStr . "bank_code=" . $bank_code . "&";
}
if ($client_ip != "") {
	$signStr = $signStr . "client_ip=" . $client_ip . "&";
}
if ($extend_param != "") {
	$signStr = $signStr . "extend_param=" . $extend_param . "&";
}
if ($extra_return_param != "") {
	$signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
}

$signStr = $signStr . "input_charset=" . $input_charset . "&";
$signStr = $signStr . "interface_version=" . $interface_version . "&";
$signStr = $signStr . "merchant_code=" . $merchant_code . "&";
$signStr = $signStr . "notify_url=" . $notify_url . "&";
$signStr = $signStr . "order_amount=" . $order_amount . "&";
$signStr = $signStr . "order_no=" . $order_no . "&";
$signStr = $signStr . "order_time=" . $order_time . "&";

if ($pay_type != "") {
	$signStr = $signStr . "pay_type=" . $pay_type . "&";
}

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
if ($redo_flag != "") {
	$signStr = $signStr . "redo_flag=" . $redo_flag . "&";
}
if ($return_url != "") {
	$signStr = $signStr . "return_url=" . $return_url . "&";
}

$signStr = $signStr . "service_type=" . $service_type;

if ($show_url != "") {

	$signStr = $signStr . "&show_url=" . $show_url;
}


/////////////////////////////   获取sign值（RSA-S加密）  /////////////////////////////////

$merchant_private_key = openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);

$postdata = array(
	'sign' => $sign,
	'merchant_code' => $merchant_code,
	'bank_code' => $bank_code,
	'order_no' => $order_no,
	'order_amount' => $order_amount,
	'service_type' => $service_type,
	'input_charset' => $input_charset,
	'notify_url' => $notify_url,
	'interface_version' => $interface_version,
	'sign_type' => $sign_type,
	'order_time' => $order_time,
	'product_name' => $product_name,
	'client_ip' => $client_ip,
	'extend_param' => $extend_param,
	'extra_return_param' => $extra_return_param,
	'pay_type' => $pay_type,
	'product_code' => $product_code,
	'product_desc' => $product_desc,
	'product_num' => $product_num,
	'return_url' => $return_url,
	'show_url' => $show_url,
	'redo_flag' => $redo_flag
);

?>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		</head>
		<body onLoad="document.dinpayForm.submit();">
			<form name="dinpayForm" method="post" action="https://pay.yuanruic.com/gateway?input_charset=UTF-8">
				<input type="hidden" name="sign"		  value="<?php echo $sign ?>" />
				<input type="hidden" name="merchant_code" value="<?php echo $merchant_code ?>" />
				<input type="hidden" name="bank_code"     value="<?php echo $bank_code ?>"/>
				<input type="hidden" name="order_no"      value="<?php echo $order_no ?>"/>
				<input type="hidden" name="order_amount"  value="<?php echo $order_amount ?>"/>
				<input type="hidden" name="service_type"  value="<?php echo $service_type ?>"/>
				<input type="hidden" name="input_charset" value="<?php echo $input_charset ?>"/>
				<input type="hidden" name="notify_url"    value="<?php echo $notify_url ?>">
				<input type="hidden" name="interface_version" value="<?php echo $interface_version ?>"/>
				<input type="hidden" name="sign_type"     value="<?php echo $sign_type ?>"/>
				<input type="hidden" name="order_time"    value="<?php echo $order_time ?>"/>
				<input type="hidden" name="product_name"  value="<?php echo $product_name ?>"/>
				<input Type="hidden" Name="client_ip"     value="<?php echo $client_ip ?>"/>
				<input Type="hidden" Name="extend_param"  value="<?php echo $extend_param ?>"/>
				<input Type="hidden" Name="extra_return_param" value="<?php echo $extra_return_param ?>"/>
				<input Type="hidden" Name="pay_type"  value="<?php echo $pay_type ?>"/>
				<input Type="hidden" Name="product_code"  value="<?php echo $product_code ?>"/>
				<input Type="hidden" Name="product_desc"  value="<?php echo $product_desc ?>"/>
				<input Type="hidden" Name="product_num"   value="<?php echo $product_num ?>"/>
				<input Type="hidden" Name="return_url"    value="<?php echo $return_url ?>"/>
				<input Type="hidden" Name="show_url"      value="<?php echo $show_url ?>"/>
				<input Type="hidden" Name="redo_flag"     value="<?php echo $redo_flag ?>"/>
				<input Type="submit" Name="Submit" value="提交"/>
			</form>
		</body>
	</html>