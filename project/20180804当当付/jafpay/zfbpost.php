<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

function curl_post($url, $data)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
      echo(curl_errno($ch));
      exit;
    }
    curl_close($ch);
    return $tmpInfo;
  }

function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}


$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.qq_returnUrl,t1.qq_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wy_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wy_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


// 參數設定
$url = "passivePay";
$merchno = $pay_mid;
$amount = $_REQUEST['MOAmount'];
$authno = "2"; //商户网站与本系统平台间的被扫接口
$traceno = getOrderNo();
$payT = "1"; //1-支付宝 2-微信 8-QQ钱包
$notifyUrl = $merchant_url;
$goodsName = "test";
$settleType = "1"; //扫码专用
if (_is_mobile()) {
	$authno = "4";  //商户网站与本系统平台间的WAP接口
	$url = "wapPay";
	$ip = getClientIp();
}
$form_url = "http://api.xunchangtong.cn/" . $url;


$params = array(
	"merchno" => $merchno,
	"amount" => $amount,
	"authno" => $authno,
	"traceno" => $traceno,
	"payType" => $payT,
	"notifyUrl" => $notifyUrl,
	"goodsName" => $goodsName,
	"settleType" => $settleType
);
if (_is_mobile()) {
	$params['ip'] = $ip;
}


ksort($params);

$postData = "";
foreach ($params as $key => $value) {
	$postData .= $key . "=" . $value . "&" ;
}

$sign = md5($postData . $pay_mkey);

$postData .= "signature=" . $sign;


$payType = $pay_type."_zfb";
$bankname = $pay_type . "->支付宝在线充值";

$result_insert = insert_online_order($_REQUEST['S_Name'], $traceno, number_format($amount, 2, '.', ''), $bankname, $payType, $top_uid);

$result = curl_post($form_url, $postData);

$response = iconv('GB2312', 'UTF-8', $result);
$respJson = json_decode($response, 1);

if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
if ( $respJson['respCode']=="00") {
	if (_is_mobile()) {
		header("location:" . $respJson["barCode"]);
	}else {
		header("location:" . '../qrcode/qrcode.php?type=zfb&code=' . QRcodeUrl($respJson["barCode"]));
	}
}else{
  echo $respJson['message'];
}

?>
