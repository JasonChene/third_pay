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
$qqscan = false;
if (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包"))
{
    $qqscan = true;
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
$return_url = $row['pay_domain'].$row['wx_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wx_synUrl'];
$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}


$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号
$amount = round($_REQUEST['MOAmount'],2);//订单金额

if ($qqscan)
    {
		$bankname = $row['pay_type']."->qq钱包在线充值";
		$payType = $row['pay_type']."_qq";
    }
    else
    {
        $bankname = $row['pay_type']."->微信在线充值";
        $payType = $row['pay_type']."_wx";
    }
	$result_insert = insert_online_order($_REQUEST['S_Name'] , $orderno , $amount,$bankname,$payType,$top_uid);
				
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


$post_data['model'] = "QR_CODE";
$post_data['merchantCode'] = $pay_mid;
$post_data['outOrderId'] = $orderno;
$post_data['deviceNo'] = "";
$post_data['amount'] = $amount *100;
$post_data['goodsName'] = "GOOD";
$post_data['goodsExplain'] = "";
$post_data['ext'] = "";
$post_data['orderCreateTime'] =  date("YmdHis");
$post_data['lastPayTime'] = date("YmdHis",strtotime("+1 days"));
$post_data['noticeUrl'] = $merchant_url;
$post_data['goodsMark'] = "";
$post_data['isSupportCredit'] = "0";
$post_data['ip'] = getClientIp();
$post_data['payChannel'] = $qqscan?"31":"21";

$temp="";
$temp = $temp."amount=".$post_data['amount']."&";
$temp = $temp."isSupportCredit=".$post_data['isSupportCredit']."&";
$temp = $temp."merchantCode=".$post_data['merchantCode']."&";
$temp = $temp."noticeUrl=".$post_data['noticeUrl']."&";
$temp = $temp."orderCreateTime=".$post_data['orderCreateTime']."&";
$temp = $temp."outOrderId=".$post_data['outOrderId']."&";
$temp = $temp."KEY=".$pay_mkey;

$post_data['sign']=strtoupper(MD5($temp));

$con = curl_init((string)"http://gateway.clpayment.com/scan/entrance.do");
 curl_setopt($con, CURLOPT_HEADER, false);
 curl_setopt($con, CURLOPT_POSTFIELDS, http_build_query($post_data));
 curl_setopt($con, CURLOPT_POST,true);
 curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($con);


//var_dump($response);
$res = json_decode($response,true);
$code = $res['code'];
if($code=="00"){
    $qrcode = $res['data']['url'];
	if ($qqscan)
	{
	    $qrcode = str_replace('&', 'aabbcc',htmlspecialchars_decode($qrcode));
		header("location:" . '../qrcode/qrcode.php?type=qq&code=' . $qrcode);	
	}
	else 
	{
		header("location:" . '../qrcode/qrcode.php?type=wx&code=' . $qrcode);
	}
 
}
?>
</head>
