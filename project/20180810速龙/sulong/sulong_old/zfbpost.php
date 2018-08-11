<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
function QRcodeUrl($code)
{
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
}

function Jump_to_Url($url, $type)
{
  if (_is_mobile()) {
    header("location:" . $url);
  } else {
    if (strstr($url, "&")) {
      $qrurl = QRcodeUrl($url);
    } else {
      $qrurl = $url;
    }
    header("location:" . '../qrcode/qrcode.php?type=' . $type . '&code=' . $qrurl);
  }
}

function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
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
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['zfb_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['zfb_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$scan2 = "pc";
if (_is_mobile()) {
  $scan2 = "h5";
}

if ($scan2 == "h5") {
  $form_url = 'https://pay.islpay.hk/gateway?input_charset=UTF-8';  //提交地址
  $interface_version = "V3.0";//接口版本
  $service_type = 'h5_ali';
} else {
  $form_url = 'https://api.islpay.hk/gateway/api/scanpay';  //提交地址
  $service_type = 'alipay_scan';
  $interface_version = "V3.1";//接口版本
}

$merchant_code = $pay_mid;  //商戶號
$publickey = $pay_account;//商户公钥
$order_amount = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$order_no = getOrderNo();  //随机生成商户订单编号
$notify_url = $merchant_url;  //异步
$extra_return_param = $return_url;//同步
$client_ip = getClientIp();
$sign_type = "RSA-S";//签名法
$order_time = trim(date("Y") . "-" . date("m") . "-" . date("d") . " " . date("H") . ":" . date("i") . ":" . date("s"));//时间
$product_name = "test";//商品名称
$input_charset = "UTF-8";
$parms = array(
  "merchant_code" => $merchant_code,//商户号
  "service_type" => $service_type,//支付类型
  "interface_version" => $interface_version,
  "client_ip" => $client_ip,
  "sign_type" => $sign_type,
  "order_amount" => $order_amount,//金额
  "order_no" => $order_no,//订单号
  "product_name" => $product_name,
  "order_time" => $order_time,
  "notify_url" => $notify_url //异步地址 
);
if ($scan2 == "h5") {
  $parms['input_charset'] = $input_charset;
  $parms['extra_return_param'] = $extra_return_param;
}
ksort($parms);
$signtext = '';
foreach ($parms as $arr_key => $arr_value) {
  if ($arr_key == "sign_type") {
  } else {
    $signtext .= $arr_key . '=' . $arr_value . '&';
  }
}
$signtext2 = substr($signtext, 0, -1);

$merchant_private_key = openssl_get_privatekey($pay_mkey);
openssl_sign($signtext2, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
$sign = base64_encode($sign_info);
$parms['sign'] = $sign;

$bankname = $pay_type . "->支付宝在线充值";
$payType = $pay_type . "_zfb";

$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $order_amount, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
if ($scan2 == "pc") {
  $res = curl_post($form_url, $parms);
  $xml = (array)simplexml_load_string($res) or die("Error: Cannot create object");
  $rep = json_decode(json_encode($xml), 1);//XML回传资料
  if ($rep['response']['resp_code'] == "SUCCESS" && $rep['response']['result_code'] == "0") {
    Jump_to_Url($rep['response']['qrcode'], 'zfb');
  } else {
    echo $rep['response']['resp_desc'] . '<br>';
    echo $rep['response']['result_desc'] . '<br>';
  }
} else {
  ?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
    <p>正在为您跳转中，请稍候......</p>
    <?php foreach ($parms as $arr_key => $arr_value) { ?>      
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
    <?php 
  } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
   </body>
 </html>
<?php 
} ?>