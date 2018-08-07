<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

//獲取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
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

// 佰钱付參數設定
$form_url = "http://bq.baiqianpay.com/webezf/web/?app_act=openapi/bq_pay/pay"; // 提交地址
$X1_Amount = number_format($_REQUEST['MOAmount'], 2, '.', ''); // 訂單支付金額,小數點兩位
$X2_BillNo = getOrderNo(); // 商户订单号
$X3_MerNo = $pay_mid; // 商戶號
$X4_ReturnURL = $merchant_url; // 服务器异步通知URL
$X5_NotifyURL = $return_url; // 支付跳轉網址
$X6_MD5info = "";
$X7_PaymentType = "WXSM"; // 不传默认网关支付
$X8_MerRemark = ""; // 备注
$X9_ClientIp = getClientIp(); // IP
$isApp = "app";

$scan = "wx";
$payType = $pay_type . "_wx";
$bankname = $pay_type . "->微信在线充值";
if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $scan = "jd";
  $X7_PaymentType = 'JDSM';
  $payType = $pay_type . "_jd";
  $bankname = $pay_type . "->京东钱包在线充值";
}
if ($scan == "wx" && _is_mobile()) {
  $X7_PaymentType = 'WXH5'; //手机微信
  $isApp = "";
}

// signText Array
$parms = array(
  'X1_Amount' => $X1_Amount,
  'X2_BillNo' => $X2_BillNo,
  'X3_MerNo' => $X3_MerNo,
  'X4_ReturnURL' => $X4_ReturnURL
);
$signText = '';
$X6_MD5info = '';
foreach ($parms as $key => $val) {
  $signText .= $key . '=' . $val . '&';
}
$signText .= strtoupper(MD5($pay_mkey));
$X6_MD5info = strtoupper(MD5($signText));

// data Array
$data = '';
$parms2 = array(
  'X1_Amount' => $X1_Amount,
  'X2_BillNo' => $X2_BillNo,
  'X3_MerNo' => $X3_MerNo,
  'X4_ReturnURL' => $X4_ReturnURL,
  'X5_NotifyURL' => $X5_NotifyURL,
  'X6_MD5info' => $X6_MD5info,
  'X7_PaymentType' => $X7_PaymentType,
  'X8_MerRemark' => $X8_MerRemark,
  'X9_ClientIp' => $X9_ClientIp,
  'isApp' => $isApp
);
foreach ($parms2 as $key => $val) {
  $data .= $key . '=' . $val . '&';
}
$data = substr($data, 0, -1);

$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
//確認訂單有無重複， function在 moneyfunc.php 裡
//insert_online_order($_REQUEST['S_Name'],訂單編號,支付金額,$bankname, $payType, $top_uid)
$result_insert = insert_online_order($_REQUEST['S_Name'], $X2_BillNo, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
$geturl = 'http://bq.baiqianpay.com/webezf/web/?app_act=openapi/bq_pay/pay&' . $data;

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//FALSE 禁止 cURL 验证对等证书（peer's certificate）。
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//设置为 1 是检查服务器SSL证书中是否存在一个公用名(common name)。
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');//在HTTP请求中包含一个"User-Agent: "头的字符串。
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//抓跳轉網址
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 35000);//时间限定35*1000毫秒
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_URL, $geturl);//需要获取的 URL 地址
$response = curl_exec($ch);
$get_info = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);
$array = json_decode($response, 1);

if (_is_mobile() && $scan != "jd") {
  header('Location:' . $get_info);
} else {
  if ($scan == "jd") {
    header('Location:' . '../qrcode/qrcode.php?type=jd&code=' . $array["imgUrl"]);
  } else {
    header('Location:' . '../qrcode/qrcode.php?type=wx&code=' . $array["imgUrl"]);
  }
}
?>