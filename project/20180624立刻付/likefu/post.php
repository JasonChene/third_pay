<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-提交信息处理</title>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];
if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料

$ylscan = false;
if (strstr($_REQUEST['pay_type'], "银联钱包")) {
	$ylscan = true;
}

$ylkj = false;
if (strstr($_REQUEST['pay_type'], "银联快捷")) {
	$ylkj = true;
}

$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";

$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_terid = $row['mer_terid'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $row['pay_type'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);//流水号
$paytype = 20; //20代表网银支付，22代表支付宝支付，30代表微信支付
$paycode = "";
$usercode = $pay_mid;//商户号
$value = round($_REQUEST['MOAmount'], 2);//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址
$dateis = date('is');
$remark = $dateis . md5($pay_mid . $dateis);//订单附加消息
$datetime = date("YmdHis", time());//交易时间
$goodsname = "GOODS NAME";//产品名称

$Md5key = $pay_mkey;//md5密钥（KEY）

//MD5签名格式
$Signature = md5("usercode=" . $usercode . "&orderno=" . $orderno . "&datetime=" . $datetime . "&paytype=" . $paytype . "&value=" . $value . "&notifyurl=" . $notifyurl . "&returnurl=" . $returnUrl . "&key=" . $Md5key);

$payUrl = "https://gateway.nowtopay.com/NowtoPay.html";
if ($ylscan) {
	$bankname = $pay_type . "->银联钱包在线充值";
	$payType = $pay_type . "_yl";
} elseif ($ylkj) {
	$bankname = $pay_type . "->银联快捷在线充值";
	$payType = $pay_type . "ylkj";
} else {
	$bankname = $pay_type . "->网银在线充值";
	$payType = $pay_type . "_wy";
}
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderno, $value, $bankname, $payType, $top_uid);

if ($result_insert == -1) {
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
} else if ($result_insert == -2) {
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}
$apiurl = $payUrl;/*接口提交地址*/
//echo "===".$apiurl;
//exit;
$partner = $pay_mid;
$key = $pay_mkey;
$ordernumber = $orderno;
if ($ylscan) {
	$banktype = "MSUNIONPAY";
} elseif ($ylkj) {
	$banktype = "MSUNIONPAYWAP";
} else {
	// $banktype = 'PAYMODE';
	$banktype = $_REQUEST['bank_code'];
}
$attach = "GOODS";
$paymoney = $value;
$callbackurl = $notifyurl;
$hrefbackurl = $pay_account;
$signSource = sprintf("partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
$sign = md5($signSource);
$postUrl = $apiurl . "?banktype=" . $banktype;
$postUrl .= "&partner=" . $partner;
$postUrl .= "&paymoney=" . $paymoney;
$postUrl .= "&ordernumber=" . $ordernumber;
$postUrl .= "&callbackurl=" . $callbackurl;
$postUrl .= "&hrefbackurl=" . $hrefbackurl;
$postUrl .= "&attach=" . $attach;
$postUrl .= "&sign=" . $sign;
//header ("location:$postUrl");
//exit;

?>
</head>
<body onLoad="document.ddbill.submit();">
	<form name="ddbill" id="ddbill" method="get" action="<?php echo $apiurl ?>">
			<input type="hidden" name="banktype"		  value="<?php echo $banktype ?>" />
			<input type="hidden" name="partner" value="<?php echo $partner ?>" />
			<input type="hidden" name="paymoney"     value="<?php echo $paymoney ?>"/>
			<input type="hidden" name="ordernumber"      value="<?php echo $ordernumber ?>"/>
			<input type="hidden" name="callbackurl"  value="<?php echo $callbackurl ?>"/>
			<input type="hidden" name="hrefbackurl"  value="<?php echo $hrefbackurl ?>"/>
			<input type="hidden" name="attach"     value="<?php echo $attach ?>"/>
			<input type="hidden" name="sign"  value="<?php echo $sign ?>"/>
		</form>
	</body>
</body>
</html>
