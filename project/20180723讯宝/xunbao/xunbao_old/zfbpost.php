﻿<?php session_start(); ?>
<?php
include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];

if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];

$pay_type = $row['pay_type'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);//流水号
$paytype = 20; //20代表网银支付，22代表支付宝支付，30代表支付宝支付
$paycode = "";
$usercode = $pay_mid;//商户号
$value = round($_REQUEST['MOAmount'], 2);//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址
$dateis = date('is');
$remark = $dateis . md5($pay_mid . $dateis);//订单附加消息
$datetime = date("YmdHis", time());//交易时间
$goodsname = "网购商品";//产品名称

$Md5key = $pay_mkey;//md5密钥（KEY）

//MD5签名格式
$Signature = md5("usercode=" . $usercode . "&orderno=" . $orderno . "&datetime=" . $datetime . "&paytype=" . $paytype . "&value=" . $value . "&notifyurl=" . $notifyurl . "&returnurl=" . $returnUrl . "&key=" . $Md5key);

$payUrl = "http://gateway.xunbaopay9.com/chargebank.aspx";//借贷混合

$bankname = $pay_type . "-支付宝在线充值";
$payType = $pay_type . "_wx";
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderno, $value, $bankname, $payType, $top_uid);

if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}
$parter = $pay_mid;
$type = (_is_mobile()) ? '931' :"8012" ;
$value = $value;
$orderid = $orderno;
$callback = $notifyurl;
$hrefback = $notifyurl;
$check_key = $pay_mkey;
$payerIp = $_SERVER["REMOTE_ADDR"];
$agent = "";
$attach = '';
$md5 = "parter=" . $parter . "&type=" . $type . "&value=" . $value . "&orderid=" . $orderid . "&callbackurl=" . $callback . $check_key;
$sign = md5($md5);
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>支付宝网页版</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/index.css" media="all">
</head>
<body onLoad="document.ddbill.submit();">
	<form name="ddbill" id="ddbill" method="post" action="<?php echo $payUrl ?>">
			<input type="hidden" name="parter"		  value="<?php echo $parter ?>" />
			<input type="hidden" name="type"		  value="<?php echo $type ?>" />
			<input type="hidden" name="value"		  value="<?php echo $value ?>" />
			<input type="hidden" name="orderid"		  value="<?php echo $orderid ?>" />
			<input type="hidden" name="callbackurl"		  value="<?php echo $notifyurl ?>" />
			<input type="hidden" name="hrefbackurl"		  value="<?php echo $notifyurl ?>" />
			<input type="hidden" name="payerIp"		  value="<?php echo $payerIp ?>" />
			<input type="hidden" name="attach"		  value="<?php echo $attach ?>" />
			<input type="hidden" name="sign"		  value="<?php echo $sign ?>" />
			<input type="hidden" name="agent"		  value="<?php echo $agent ?>" />
		</form>
	</body>
</html>