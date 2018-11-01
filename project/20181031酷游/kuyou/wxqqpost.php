<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    echo (curl_errno($ch));
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

$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$form_url = 'http://saas.yeeyk.com/saas-trx-gateway/order/acceptOrder'; //提交地址
$trxMerchantNo = $pay_mid;  //商戶號
$trxMerchantOrderno = getOrderNo();  //随机生成商户订单编号
$memberGoods = $trxMerchantOrderno;  //必须使用商户订单号
$requestAmount = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$noticeSysaddress = $merchant_url;  //异步
if (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包")) {
  $scan ='qq';
  $payType = $pay_type . "_qq";
  $bankname = $pay_type . "->QQ钱包在线充值";
  $productNo = "QQWALLET-JS";//产品编号
if (_is_mobile()) {
  $productNo = "QQWALLETWAP-JS";//产品编号
}
}else {
  $scan ='wx';
  $payType = $pay_type . "_wx";
  $bankname = $pay_type . "->微信在线充值";
  $productNo = "WX-JS";//产品编号
}

$parms = array(
  "trxMerchantNo" => $trxMerchantNo,
  "trxMerchantOrderno" => $trxMerchantOrderno,
  "memberGoods" => $memberGoods,
  "requestAmount" => $requestAmount,
  "noticeSysaddress" => $noticeSysaddress,
  "productNo" => $productNo
);

ksort($parms);
$signtext = '';

foreach ($parms as $arr_key => $arr_value) {
  if ($arr_value == "" || $arr_value == null || $arr_key == "signtype") {
  } else {
    $signtext .= $arr_key . '=' . $arr_value . '&';
  }
}
$signtext2 = substr($signtext, 0, -1) . "&key=" . $pay_mkey;
$sign = mb_strtolower(md5($signtext2));
$parms['hmac'] = $sign;



$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$result_insert = insert_online_order($_REQUEST['S_Name'], $trxMerchantOrderno, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

$res = curl_post($form_url, $parms);
$rep = json_decode($res, 1);

if ($rep['code'] == "00000") {
    header("location:" . $rep["payUrl"]);
  exit;
} else {
  echo $rep['code'] . '<br>' . $rep['message'];
};

?>