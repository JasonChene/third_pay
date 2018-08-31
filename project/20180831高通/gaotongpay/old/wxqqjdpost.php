<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];

function get_client_ip() {
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

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
$qqscan = false;
if (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包"))
{
    $qqscan = true;
}
$jdscan = false;
if (strstr($_REQUEST['pay_type'], "京东钱包"))
{
    $jdscan = true;
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
$payip = get_client_ip();

//提交地址
#$form_url = 'https://pay.ips.net.cn/ipayment.aspx'; //测试
$form_url = 'http://tj.gaotongpay.com/PayBank.aspx'; //正式

if ($qqscan)
{
    $bankType = _is_mobile()?"QQPAYWAP":"QQPAY";
}
else if($jdscan)
{
    $bankType = _is_mobile()?"JDPAYWAP":"JDPAY";
}
else
{
    $bankType = _is_mobile()?"WEIXINWAP":"WEIXIN";
    if(strstr($_REQUEST['pay_type'],"微信反扫")){
    	$bankType = "WEIXINBARCODE";
    }

}

$data = array();
//商户号
$data['partner'] = $pay_mid;
$data['banktype'] = $bankType;
//订单金额(保留2位小数)
$data['paymoney'] = number_format($_REQUEST['MOAmount'], 2, '.', ''); 
//商户订单编号
$orderid = date("YmdHis") . substr(microtime(), 2, 5) . rand(1000, 2000);
$data['ordernumber'] = $orderid;
//支付结果成功返回的商户URL
$data['callbackurl'] = $merchant_url;
$data['hrefbackurl'] = $return_url;

$dateis = date('is');
//商户数据包
$data['attach'] = $_REQUEST['S_Name']."|".$dateis."|".md5($_REQUEST['S_Name'].$pay_mid.$dateis);

$url = "";

foreach ($data as $key => $v) {
    if ($key !== 'hrefbackurl' and $key !== 'attach'){    #hrefbackurl 不参与签名
        $url = $url .  $key  . '='  . $v  .  '&';
    }
}
$url = substr($url, 0,strlen($url) - 1) . $pay_mkey;
$data['sign'] = md5($url);

if ($qqscan)
{
    $bankname = $pay_type."->qq钱包在线充值";
    $payType = $pay_type."_qq";    
}
else if($jdscan){
    $bankname = $pay_type."->京东钱包在线充值";
    $payType = $pay_type."_jd";
}
else {
    $bankname = $pay_type."->微信在线充值";
    $payType = $pay_type."_wx";
}

$result_insert = insert_online_order($_REQUEST['S_Name'] , $data['ordernumber'] , $data['paymoney'],$bankname,$payType,$top_uid);
			
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

$pay_url = $form_url . '?' .http_build_query($data);
header("location:" . $pay_url);
?>
