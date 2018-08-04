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


$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}


//獲取第三方的资料
$pay_type = urldecode($_REQUEST['pay_type']);
$params = array(':pay_type' => urldecode($_REQUEST['pay_type']));
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key']; //商戶公鑰
$pay_account = $row['mer_account']; //商戶私鑰
$return_url = $row['pay_domain'] . $row['wy_returnUrl'];//return跳轉地址
$merchant_url = $row['pay_domain'] . $row['wy_synUrl'];//notify回傳地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

if (strstr($_REQUEST['pay_type'], "银联快捷")){
  $scan = 'ylkj';
}else {
  $scan = 'wy';
}

// 參數設定
if ($scan == 'ylkj') {
  $form_url = "http://api.wachou.top/h5Quick.do";
}else {
  $form_url = "http://api.wachou.top/gateway.do?m=order";
}
$merchno = $pay_mid;
$amount = $_REQUEST['MOAmount'];
$traceno = getOrderNo();
$channel = "2";
$bankCode = $_REQUEST["bank_code"];
$settleType = "2"; //网银专用
$notifyUrl = $merchant_url;
$returnUrl = $return_url;



if ($scan == 'ylkj') {
  $params = array(
    "interType" => '2',
    "merchno" => $merchno,
    "traceno" => $traceno,
    "amount" => $amount,
    "settleType" => $settleType,
    "cardType" => '1',//借记卡
    "notifyUrl" => $notifyUrl,
    "returnUrl" => $returnUrl
  );
}else {
  $params = array(
    "merchno" => $merchno,
    "amount" => $amount,
    "traceno" => $traceno,
    "channel" => $channel,
    "bankCode" => $bankCode,
    "settleType" => $settleType,
    "notifyUrl" => $notifyUrl,
    "returnUrl" => $returnUrl
  );
}



ksort($params);

$postData = "";
foreach ($params as $key => $value) {
	$postData .= $key . "=" . $value . "&" ;
}

$sign = md5($postData . $pay_mkey);

$postData .= "signature=" . $sign;

if ($scan == 'ylkj') {
  $payType = $pay_type."_ylkj";
  $bankname = $pay_type . "->银联快捷在线充值";
}else {
  $payType = $pay_type."_wy";
  $bankname = $pay_type . "->网银在线充值";
}



// insert_online_order($_REQUEST['S_Name'],訂單編號,支付金額,$bankname, $payType, $top_uid)
$result_insert = insert_online_order($_REQUEST['S_Name'], $traceno, number_format($amount, 2, '.', ''), $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

$return = curl_post($form_url, $postData);


$response = iconv('GBK', 'UTF-8', $return);

echo $response;


?>
