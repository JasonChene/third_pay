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

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";

$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
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
	echo "非法提交参数[11]";
	exit;
}

$orderno = getOrderNo();//流水号
$paytype = 20; //20代表网银支付，22代表支付宝支付，30代表微信支付
$paycode = "";
$usercode = $pay_mid;//商户号
$value = number_format($_REQUEST['MOAmount'], 2, '.', '');//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址
$dateis = date('is');
$remark = $dateis.md5($pay_mid.$dateis);//订单附加消息
$datetime = date("YmdHis",time());//交易时间
$goodsname = "网购商品";//产品名称

$payUrl="http://zf.szjhzxxkj.com/ownPay/pay";

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

$public_key = $pay_account;
$private_key = $pay_mkey;
// 请求数据赋值
$data = array();

$data['merchantNo'] =  $pay_mid;
$data['requestNo'] =  $orderno; //支付流水
$data['amount'] = number_format($_REQUEST['MOAmount']*100, 0, '.', '');//金额（分）
$data['payMethod'] = '6002';//业务代码
$data['backUrl'] = $notifyurl;   //服务器返回URL
$data['pageUrl'] = $returnurl;   //页面返回URL
$data['agencyCode'] = '';
$data['payDate'] = time();   //支付时间，必须为时间戳
$data['remark1'] = 'GOODS'; 
$data['remark2'] ='';
$data['remark3'] = '';

$signature=$pay_mid."|".$data['requestNo']."|".$data['amount']."|".$data['pageUrl']."|".$data['backUrl']."|".$data['payDate']."|".$data['agencyCode']."|".$data['remark1']."|".$data['remark2']."|".$data['remark3'];

$data['cur'] = 'CNY';
$data['bankType'] = $_REQUEST['bank_code'];
$data['bankAccountType'] = '11';
$data['timeout'] = '';


$pr_key ='';
if(openssl_pkey_get_private($private_key)){
    $pr_key = openssl_pkey_get_private($private_key);
}else{
    echo '获取private key失败！';
    echo '<br>';
    exit;
}
$pu_key = '';
if(openssl_pkey_get_public($public_key)){
    $pu_key = openssl_pkey_get_public($public_key);
}else{
    echo '获取public key失败！';
    echo '<br>';
    exit;
}


$sign = '';

//openssl_sign(加密前的字符串,加密后的字符串,密钥:私钥);
openssl_sign($signature,$sign,$pr_key);


openssl_free_key($pr_key);

$sign = base64_encode($sign);



$data['signature'] = $sign;


$sHtml = "<form id='youbaopaysubmit' name='youbaopaysubmit' action='".$payUrl."' method='get'>";
while (list ($key, $val) = each ($data)) {
    $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
}
$sHtml.= "</form>";
$sHtml.= "<script>document.forms['youbaopaysubmit'].submit();</script>";

echo $sHtml;

?>
</head>
<body></body>
</html>