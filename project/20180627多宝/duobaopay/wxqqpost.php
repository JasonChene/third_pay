<? header("content-Type: text/html; charset=utf-8"); ?>
<?php
/* *
 *功能：即时到账交易接口接入页
 *版本：3.0
 *日期：2013-08-01
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
$top_uid = $_REQUEST['top_uid'];
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
////////////////////////////////////请求参数//////////////////////////////////////

function get_client_ip()
{
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_X_REAL_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_REAL_FORWARDED_FOR'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	return $ip;
}
if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}

//获取第三方的资料
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $row['pay_type'];
$domain = $row['pay_domain'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$arr = array();

$arr['callbackurl'] = $merchant_url;
$arr['hrefbackurl'] = $return_url;

if (strstr($pay_type, "QQ钱包") || strstr($pay_type, "qq钱包")) {
	$scan = 'qq';
	$arr['type'] = '993'; //QQ bs
	if (_is_mobile()) {
		$arr['type'] = '1102'; //QQ H5
	}
	$bankname = $pay_type . "->QQ钱包在线充值";
	$payType = $pay_type . "_qq";
} else {
	$scan = 'wx';
	$arr['type'] = "1004";
	if (_is_mobile()) {
		$arr['type'] = "1100";
	}
	$bankname = $pay_type . "->微信在线充值";
	$payType = $pay_type . "_wx";
}

$arr['parter'] = $pay_mid;
$arr['noneddkf'] = "Y";

//商家定单号(必填)
function getOrderId()
{
	return rand(100000, 999999) . "" . date("YmdHis");
}
$arr['orderid'] = getOrderId();
//定单金额（必填）
$arr['value'] = round($_REQUEST['MOAmount'], 2);

//公用业务扩展参数（选填）
$dateis = date('is');

//公用业务回传参数（选填）
$arr['attach'] = $_REQUEST['S_Name'] . "|" . $dateis . "|" . md5($_REQUEST['S_Name'] . $pay_mid . $dateis);


$buff = "parter=" . $arr['parter'] . "&type=" . $arr['type'] . "&value=" . $arr['value'] . "&orderid=" . $arr['orderid'] . "&callbackurl=" . $arr['callbackurl'] . $pay_mkey;//."&hrefbackurl=".$arr['hrefbackurl']

// 计算签名
$sign = md5($buff);
$arr['sign'] = $sign;

$gateway = "https://gwbb69.169.cc/interface/AutoBank/index.aspx";

$result_insert = insert_online_order($_REQUEST['S_Name'], $arr['orderid'], $arr['value'], $bankname, $payType, $top_uid);

if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}


?>
<html>
<head>
<title>网关支付</title>
</head>
<body onLoad="document.dinpayForm.submit();">
正在跳转 ...
<form name="dinpayForm" method="post" action="<?php echo $gateway; ?>"><!-- 注意 非UTF-8编码的商家网站 此地址必须后接编码格式 -->
<?
foreach ($arr as $x => $x_value) {
	if ($x_value != "" && !is_array($x_value)) {
		?>
		<input type="hidden" name="<?= $x ?>" value="<?= $x_value ?>" />
		<?
}
}
?>
</form>
</body>
</html>