<?php session_start(); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];
if(function_exists("date_default_timezone_set")){
	date_default_timezone_set("Asia/Shanghai");
}

//获取第三方的资料
$jdscan = false;
if (strstr($_REQUEST['pay_type'], "京东钱包"))
{
    $jdscan = true;
}
$bdscan = false;
if (strstr($_REQUEST['pay_type'], "百度钱包"))
{
    $bdscan = true;
}
$wxfsscan = false;
if (strstr($_REQUEST['pay_type'], "微信反扫"))
{
    $wxfsscan = true;
}
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'].$row['wx_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wx_synUrl'];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号
$value = number_format($_REQUEST['MOAmount'],2,".","");//订单金额
if($jdscan)
{
$bankname = $pay_type."->京东钱包在线充值";
$payType = $pay_type."_jd";
}
else if($bdscan)
{
$bankname = $pay_type."->百度钱包在线充值";
$payType = $pay_type."_bd";
}
else
{
$bankname = $pay_type."->微信在线充值";
$payType = $pay_type."_wx";
}
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


$p1_mchtid		= $pay_mid;//商户号

if($jdscan){
	if(_is_mobile()){
		$p2_paytype	= "JDPAYWAP";
	}else{
		$p2_paytype	= "JDPAY";
	}
}
else if($bdscan){
		$p2_paytype	= "BAIDUPAY";
}
else if($wxfsscan){
		$p2_paytype	= "WEIXINBARCODE";
}
else{
	if(_is_mobile()){
		$p2_paytype	= "WEIXINWAP";
	}else{
		$p2_paytype	= "WEIXIN";
	}
}
$p3_paymoney	= $value; 
$p4_orderno		= $orderno;
$p5_callbackurl = $merchant_url;
$p6_notifyurl	= $return_url;
$p7_version		= "v2.8";
$p8_signtype	= "1";
$p9_attach		= "GOOD";
$p10_appname	= "";
$p11_isshow		= "0";
$p12_orderip	= getClientIp();

$payUrl="http://pay.095pay.com/zfapi/order/pay";
$temp = "";
$temp = $temp."p1_mchtid=".$p1_mchtid."&";
$temp = $temp."p2_paytype=".$p2_paytype."&";
$temp = $temp."p3_paymoney=".$p3_paymoney."&";
$temp = $temp."p4_orderno=".$p4_orderno."&";
$temp = $temp."p5_callbackurl=".$p5_callbackurl."&";
$temp = $temp."p6_notifyurl=".$p6_notifyurl."&";
$temp = $temp."p7_version=".$p7_version."&";
$temp = $temp."p8_signtype=".$p8_signtype."&";
$temp = $temp."p9_attach=".$p9_attach."&";
$temp = $temp."p10_appname=".$p10_appname."&";
$temp = $temp."p11_isshow=".$p11_isshow."&";
$temp = $temp."p12_orderip=".$p12_orderip;
if((!(_is_mobile())||$bdscan)&& $wxfsscan == false){
$sign = md5($temp.$pay_mkey);
$temp = $temp."&sign=".$sign;

	$ch = curl_init();
	
	curl_setopt($ch,CURLOPT_URL,$payUrl);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $temp);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response=curl_exec($ch);
	//var_dump($response);
	$res = json_decode($response,true);
	if($res['rspCode']==1)
	{
		$qrcode = $res['data']['r6_qrcode'];
		$qrcode = str_replace('&', 'aabbcc',htmlspecialchars_decode($qrcode));
		if($jdscan){
			header("location:" . 'qrcode.php?type=jd&code=' . $qrcode);
		}else if($bdscan){
			header("location:" . 'qrcode.php?type=bd&code=' . $qrcode);
		}else{
			header("location:" . 'qrcode.php?type=wx&code=' . $qrcode);
		}
		exit;
	}else{
		echo 'rspCode:'.$res['rspCode'].'<br>rspMsg:'.$res['rspMsg'];
		exit;
	}

}else
{
$sign = md5($temp.$pay_mkey);
}	
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/index.css" media="all">
</head>
<body onLoad="document.ddbill.submit();">
	<form name="ddbill" id="ddbill" method="post" action="<?php echo $payUrl?>">
			<input type="hidden" name="p1_mchtid"		  value="<?php echo $p1_mchtid?>" />
			<input type="hidden" name="p2_paytype" value="<?php echo $p2_paytype?>" />
			<input type="hidden" name="p3_paymoney"     value="<?php echo $p3_paymoney?>"/>
			<input type="hidden" name="p4_orderno"      value="<?php echo $p4_orderno?>"/>
			<input type="hidden" name="p5_callbackurl"  value="<?php echo $p5_callbackurl?>"/>
			<input type="hidden" name="p6_notifyurl"  value="<?php echo $p6_notifyurl?>"/>
			<input type="hidden" name="p7_version" value="<?php echo $p7_version?>"/>
			<input type="hidden" name="p8_signtype"     value="<?php echo $p8_signtype?>"/>
			<input type="hidden" name="p9_attach"    value="<?php echo $p9_attach?>"/>
			<input type="hidden" name="p10_appname"  value="<?php echo $p10_appname?>"/>
			<input type="hidden" name="p11_isshow"  value="<?php echo $p11_isshow?>"/>
			<input type="hidden" name="p12_orderip"  value="<?php echo $p12_orderip?>"/>
			<input type="hidden" name="sign"  value="<?php echo $sign?>"/>
		</form>
	</body>
</html>
