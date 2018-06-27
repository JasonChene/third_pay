<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-提交信息处理</title>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'].$row['wy_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wy_synUrl'];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号
$value = number_format($_REQUEST['MOAmount'],2,".","");//订单金额

$bankname = $pay_type."->网银在线充值";
$payType = $pay_type."_wy";
$result_insert = insert_online_order($_REQUEST['S_Name'] , $orderno , $value,$bankname,$payType,$top_uid);
	
if ($result_insert == -1)
{
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
}
else if ($result_insert == -2)
{
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}

	$url = "http://www.jinjupay.com/api/pay/orderPay";
	$version = "1.0";
	$customerid = $pay_mid;//商户号，1118004517是测试商户号，线上发布时要更换商家自己的商户号！
	$sdorderno = $orderno;
	$total_fee = $value;
	$paytype = "bank";
	$bankcode = $_REQUEST['bank_code'];
	$notifyurl = $merchant_url;
	$returnurl = $return_url;
	$remark = "";
	$get_code = "0";
	$signSource =
sprintf("version=%s&customerid=%s&total_fee=%s&sdorderno=%s&notifyurl=%s&returnurl=%s&%s", $version, $customerid, $total_fee, $sdorderno, $notifyurl,$returnurl, $pay_mkey);

	$sign=md5($signSource);

?>
</head>

<body onLoad="document.ddbill.submit();">
	<form name="ddbill" method="post" action="<?php echo $url; ?>">
    <input type="hidden" name="version" value="<?php echo $version; ?>" />
    <input type="hidden" name="customerid" value="<?php echo $customerid; ?>" />
    <input type="hidden" name="sdorderno" value="<?php echo $sdorderno; ?>" />
    <input type="hidden" name="total_fee" value="<?php echo $total_fee; ?>" />
    <input type="hidden" name="paytype" value="<?php echo $paytype; ?>" />
    <input type="hidden" name="bankcode" value="<?php echo $bankcode; ?>" />
    <input type="hidden" name="notifyurl" value="<?php echo $notifyurl; ?>" />
    <input type="hidden" name="returnurl" value="<?php echo $returnurl; ?>" />
    <input type="hidden" name="remark" value="<?php echo $remark; ?>" />
    <input type="hidden" name="get_code" value="<?php echo $get_code; ?>" />
    <input type="hidden" name="sign" value="<?php echo $sign; ?>" />
		</form>
	</body>
</html>
