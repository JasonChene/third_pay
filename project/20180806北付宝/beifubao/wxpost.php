<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.php");
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json; charset=utf-8",
    "Content-Length: " . strlen($data))
  );
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
}
function rsa_encrypt($encrypted, $rsa_public_key){
  $crypto = '';
  foreach (str_split($encrypted, 117) as $chunk) {
      openssl_public_encrypt($chunk, $encryptDatas, $rsa_public_key);
      $crypto .= $encryptDatas;
  }
  return base64_encode($crypto);
}
function rsa_decrypt($encrypted, $rsa_private_key){
  $crypto = '';
  $encrypted = base64_decode($encrypted);
  foreach (str_split($encrypted, 128) as $chunk) {
      openssl_private_decrypt($chunk, $decryptData, $rsa_private_key);
      $crypto .= $decryptData;
  }
  return $crypto;
}
function payType_bankname($scan, $pay_type)
{
  global $payType, $bankname;
  if ($scan == "wy") {
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  } elseif ($scan == "yl" || $scan == "ylfs") {
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  } elseif ($scan == "qq" || $scan == "qqfs") {
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  } elseif ($scan == "wx" || $scan == "wxfs") {
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  } elseif ($scan == "zfb" || $scan == "zfbfs") {
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  } elseif ($scan == "jd" || $scan == "jdfs") {
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  } elseif ($scan == "ylkj") {
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  } elseif ($scan == "bd" || $scan == "bdfs") {
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  } else {
    echo ('payType_bankname出错啦！');
    exit;
  }
}
function QRcodeUrl($code)
{
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
}
#获取第三方资料

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#获取第三方资料(非必要不更动)
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
// $stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//私鑰
$pay_account = $row['mer_account'];//公鑰
$return_url = $row['pay_domain'] . $row['wx_postUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $_REQUEST['pay_type'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#固定参数设置
$public_pem = chunk_split($pay_account, 64, "\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n" . $public_pem . "-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey, 64, "\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$businessHead = array(
  "charset" => "00",//字符集
  "version" => "V1.0.0",//版本号	
  "memberNumber" => $pay_mid,//会员编号	
  "method" => "UNIFIED_PAYMENT",//请求接口名称	
  "requestTime" => date("YmdHis"),//请求时间	
  "signType" => "RSA",//签名类型	
  "sign" => ""//签名信息	
);
$businessContext = array(
  "defrayalType" => "", //支付方式	
  "memberOrderNumber" => $order_no,//会员订单号	
  "tradeCheckCycle" => "T1",//结算周期(暂时只支持T1)
  "orderTime" => date("YmdHis"),//订单时间	
  "currenciesType" => "CNY",//币种
  "tradeAmount" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),//交易金额
  "commodityBody" => "pay",//商品信息	
  "commodityDetail" => "pay",//商品详情
  "returnUrl" => $return_url,//同步通知地址	
  "notifyUrl" => $merchant_url,//异步通知地址	
  "terminalIP" => getClientIp(),//设备IP
  "attach" => "pay"//附加信息	
);

#变更参数设置
$form_url = "http://789game.wang/api/payment/unifiedPay";
$scan = 'wx';
$businessContext['defrayalType'] = "WECHAT_JSAPI";

payType_bankname($scan, $pay_type);
#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
#签名排列，可自行组字串或使用http_build_query($array)
ksort($businessContext);
$json_businessContext = json_encode($businessContext,320);

#RSA-S签名
$privatekey = openssl_pkey_get_private($private_pem);
if ($privatekey == false) {
  echo "打开私钥出错";
  exit();
}
$prb = openssl_sign($json_businessContext, $sign_info, $privatekey, OPENSSL_ALGO_MD5);
if ($prb) {
  $businessHead['sign'] = base64_encode($sign_info);
} else {
  echo "加密失敗";
  exit();
}

$data['businessHead'] = $businessHead;
$data['businessContext'] = $businessContext;
$json_order = json_encode($data,320);

$publickey = openssl_pkey_get_public($public_pem);
if ($publickey == false) {
  echo "打开公钥出错";
  exit();
}
$cryptos = rsa_encrypt($json_order, $publickey);
$context = array(
  'context' => $cryptos,
);
$json_context = json_encode($context);

#curl获取响应值
$res = curl_post($form_url, $json_context);
$array = json_decode($res, 1);

if ($array['success'] == true && $array['message']['code'] == 200) {
  $context_str = $array['context'];
  $context_decrypt = rsa_decrypt($context_str, $privatekey);
  $return_context = json_decode($context_decrypt, 1);
  $return_sign = $return_context['businessHead']['sign'];
  $return_businessContext = $return_context['businessContext'];
  ksort($return_businessContext);
  $return_json_businessContext = json_encode($return_businessContext,320);
  $isVerify = openssl_verify($return_json_businessContext, base64_decode($return_sign), $publickey, OPENSSL_ALGO_MD5);
  if ($isVerify != 1) {
    echo "签名验证失败！";
    exit;
  } else {
      $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($return_businessContext['content']);
  }
} else {
  echo '错误代码:' . $array['message']['code'] . "<br>";
  echo '错误讯息:' . $array['message']['content'] . "<br>";
  exit;
}

#跳轉方法
?>
<html>
<head>
  <title>跳转......</title>
  <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
</head>
<body>
  <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
    <p>正在为您跳转中，请稍候......</p>
  </form>
  <script language="javascript">
    document.getElementById("frm1").submit();
  </script>
</body>
</html>
