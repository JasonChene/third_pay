<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

function wx_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
}

function encrypt($params,$key)
{ #調用RAS加密，最后获得的加密串放入cipher_data参数中发送。
  $originalData = json_encode($params);
  $crypto = '';
  $encryptData = '';
  // $rsaPublicKey = file_get_contents($key);
  foreach (str_split($originalData,117) as $chunk) {
      openssl_public_encrypt($chunk,$encryptData,$key);
      $crypto .= $encryptData;
  }
  // echo $encryptData;
  return base64_encode($crypto);
}

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}


//獲取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//商戶金鑰
$pay_public_key = $row['mer_account'];//商戶公鑰
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳轉地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回傳地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

//芒果支付參數設定
$form_url = 'http://www.magopay.net/api/trans/pay';  //提交地址
$member_code = $pay_mid;//商戶號
if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $scan = 'jd';
  $bankname = $pay_type."->京东钱包在线充值";
  $payType = $pay_type."_jd";
  $type_code = 'jdbs'; //京东钱包被扫
  if (_is_mobile()) {
    $type_code = 'jdh5'; //京东H5
  }
}elseif (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包")) {
  $scan = 'qq';
  $payType = $pay_type."_qq";
  $bankname = $pay_type . "->QQ钱包在线充值";
  $type_code = 'qqbs'; //QQ钱包被扫
  if (_is_mobile()) {
    $type_code = 'qqh5'; //QQ钱包H5
  }
}else {
  $scan = 'wx';
  $payType = $pay_type."_wx";
  $bankname = $pay_type . "->微信在线充值";
  $type_code = 'wxbs'; //微信被扫
  if (_is_mobile()) {
    $type_code = 'wxh5'; //微信H5
  }
}

$down_sn = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);  //隨機生成商户訂單編號
$subject = 'OhYesBaby';
$amount = $_REQUEST['MOAmount'];//number_format($_REQUEST['MOAmount'], 2, '.', ''); //訂單支付金額,小數點兩位
$notify_url = $merchant_url;
$return_url = $return_url;
$card_type = ''; //网关必填  1：对私借记卡；2：对私贷记卡；3：对公借记卡；
$bank_segment = '';
$user_type = '';//网关必填  1：个人；2：企业；
$agent_type = '';//网关必填  1：PC端；2：手机；





$parms = array(
  "type_code" => $type_code,
  "down_sn" => $down_sn,
  "subject" => $subject,
  "amount" => $amount,
  "notify_url" => $notify_url,
  "return_url" => $return_url,
  "card_type" =>$card_type,
  "bank_segment" => $bank_segment,
  "user_type" => $user_type,
  "agent_type" => $agent_type,
);
$noarr = ['sign','code','msg'];
ksort($parms);
$signText = "";
foreach ($parms as $key => $val) {
  if ( !in_array($key, $noarr) && (!empty($val) || $val ===0 || $val ==='0') ) {
    $signText .= $key . '=' . $val . '&';
  }
}
$signText .= 'key='.$pay_mkey;
$sign = strtolower(md5($signText));
$parms['sign'] = $sign;

//data傳兩個參數member_code(商戶號)、cipher_data(RSA加密業務參數)
$data =[
  'member_code' => $member_code,
  'cipher_data' => encrypt($parms,$pay_public_key),
];

//確認訂單有無重複， function在 moneyfunc.php 裡
//insert_online_order($_REQUEST['S_Name'],訂單編號,支付金額,$bankname, $payT, $top_uid)
$result_insert = insert_online_order($_REQUEST['S_Name'], $down_sn, $amount, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
//調用curl
$return = wx_post($form_url,$data);
$row = json_decode($return, true); //将返回json数据转换为数组

if ($row['code'] == '0000') {
  if(_is_mobile()){
    header("location:" .  $row['code_url']);
    exit();
  } else {
    if ($jdscan) {
      header("location:" . '../qrcode/qrcode.php?type=jd&code=' . $row['code_url']);
    }else {
      header("location:" . '../qrcode/qrcode.php?type=wx&code=' . $row['code_url']);
    }
    exit();
    }
}else {
  echo $row['code']."<br>";
  echo $row['msg'];
}

?>
