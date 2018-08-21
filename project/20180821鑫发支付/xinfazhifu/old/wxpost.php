<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.config.php");
// include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
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
$stmt = $mydata1_db->prepare($sql);
// $stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$idArray = explode("###", $pay_mkey);
$md5key = $idArray[0];//md5密钥
$private_key = $idArray[1];//RSA私钥：
$pay_account = $row['mer_account'];//RSA支付公钥
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
$private_pem = chunk_split($private_key, 64, "\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";

$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  "version" => "V3.3.0.0", 
  "merchNo" => $pay_mid,
  "payType" => "",
  "randomNum" => $order_no,
  "orderNo" => $order_no,
  "amount" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),
  "goodsName" => "pay",
  "notifyUrl" => $merchant_url,
  "notifyViewUrl" => $return_url,
  "charsetCode" => "UTF-8",
);

#变更参数设置
$form_url = "http://netway.xfzfpay.com:90/api/pay";
  
if(strstr($pay_type, "百度钱包")) {
  $scan = 'bd';
  $data['payType'] = "BAIDU";
}elseif(strstr($pay_type, "微信反扫")) {
  $scan = 'wxfs';
  $data['payType'] = "WX_AUTH_CODE";
  if (_is_mobile()) {
    $data['payType'] = "WX_AUTH_CODE_WAP";
  }
}elseif(strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $data['payType'] = "JD";
  if (_is_mobile()) {
    $data['payType'] = "JD_WAP";
  }
}else {
  $scan = 'wx';
  $data['payType'] = "WX";
  if (_is_mobile()) {
    $data['payType'] = "WX_WAP";
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

$jsondata = json_encode($data,320);
$data['sign'] = strtoupper(md5($jsondata.$md5key));
#RSA
$publickey = openssl_get_publickey($public_pem);
  if ($publickey == false) {
    echo "打开公钥出错";
    exit();
}
$json = json_encode($data,320);
$encryptData = '';
$crypto = '';
foreach (str_split($json, 117) as $chunk) {
  openssl_public_encrypt($chunk, $encryptData, $publickey);
  $crypto = $crypto . $encryptData;
}

$crypto = base64_encode($crypto);
$param = 'data=' . urlencode($crypto) . '&merchNo=' . $data['merchNo'];


#curl获取响应值
$res = curl_post($form_url, $param);
$array = json_decode($res, 1);

if ($array['stateCode'] == '00') {
  $sign_string = $array['sign'];
  ksort($array);
  $sign_array = array();
  foreach ($array as $k => $v) {
    if ($k !== 'sign'){
      $sign_array[$k] = $v;
    }
  }
  $md5 =  strtoupper(md5(json_encode($sign_array,320) . $md5key));
  
  if ($md5 != $sign_string) {
    echo "返回签名验证失败";
    exit;
  } else {
    if ((_is_mobile() && $scan != 'bd') || $scan == 'wxfs') {
      $jumpurl = $array['qrcodeUrl'];
    } else {
      $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($array['qrcodeUrl']);
    }
  }
} else {
  echo '错误代码:' . $array['stateCode'] . "<br>";
  echo '错误讯息:' . $array['msg'] . "<br>";
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
