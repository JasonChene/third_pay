<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.php");
// include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
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
$data = array(
  "inputCharset" => "1",
  "partnerId" => $pay_mid,
  "signType" => "1",
  "notifyUrl" => $merchant_url,
  "returnUrl" => $return_url,
  "orderNo" => $order_no,
  "orderAmount" => number_format($_REQUEST['MOAmount'] * 100, 0, '.', ''),
  "orderCurrency" => "156",
  "orderDatetime" => date("YmdHis"),
  "signMsg" => "",
  "payMode" => "",
  "subject" => "pay",
  "body" => "pay",
  "ip" => getClientIp()
);

#变更参数设置
$form_url = "http://pay.jf690.com/pay/unify";

if (strstr($pay_type, "QQ钱包") || strstr($pay_type, "qq钱包")) {
  $scan = 'qq';
  $data['payMode'] = "5";
  if (_is_mobile()) {
    $data['payMode'] = "9";
  }
} elseif (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $data['payMode'] = "14";
  if (_is_mobile()) {
    $data['payMode'] = "11";
  }
} else {
  $scan = 'wx';
  $data['payMode'] = "1";
  if (_is_mobile()) {
    $data['payMode'] = "7";
  }
}
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
ksort($data);

$noarr = array('signMsg', 'signType');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1);
#RSA-S签名
$privatekey = openssl_get_privatekey($private_pem);
if ($privatekey == false) {
  echo "打开私钥出错";
  exit();
}
$prb = openssl_sign($signtext, $sign_info, $privatekey, OPENSSL_ALGO_SHA1);
if ($prb) {
  $data['signMsg'] = base64_encode($sign_info);
  openssl_free_key($privatekey);
} else {
  echo "加密失敗";
  exit();
}
#curl获取响应值
$res = curl_post($form_url, http_build_query($data));
$array = json_decode($res, 1);

if ($array['errCode'] == '0000' || $array['status'] == '2') {
  $ressign = base64_decode($array['signMsg']);
  $ressigntext = "";
  ksort($array);
  $noarr = array('signMsg', 'signType');
  foreach ($array as $arr_key => $arr_val) {
    if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
      $ressigntext .= $arr_key . '=' . $arr_val . '&';
    }
  }
  $ressigntext = substr($ressigntext, 0, -1);
  $publickey = openssl_get_publickey($public_pem);
  if ($publickey == false) {
    echo "打开公钥出错";
    exit();
  }
  $result = openssl_verify($ressigntext, $ressign, $publickey, OPENSSL_ALGO_SHA1);
  openssl_free_key($publickey);
  if ($result != 1) {
    echo "签名验证失败！";
    exit;
  } else {
    if (_is_mobile()) {
      $jumpurl = $array['retHtml'];
      echo $jumpurl;
      exit;
    } else {
      $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($array['qrCode']);
    }
  }
} else {
  echo '错误代码:' . $array['errCode'] . "<br>";
  echo '错误讯息:' . $array['errMsg'] . "<br>";
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
