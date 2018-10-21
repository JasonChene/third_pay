<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
  curl_close($ch);
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
{ #替换QRcodeUrl中&符号
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$public_pem = chunk_split($pay_account,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";

#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  //基本参数
  "merchant_code" => $pay_mid, //商家号
  "service_type" => '', //业务类型
  "notify_url" => $merchant_url, //服务器异步通知地址
  "interface_version" => '', //接口版本
  "client_ip" => getClientIp(), //客户端IP
  "sign_type" => 'RSA-S', //签名方式
  "sign" => '', //签名

  //业务参数
  "order_no" => $order_no, //商户网站唯一订单号
  "order_time" => date("Y-m-d H:i:s"), //商户订单时间
  "order_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //商户订单总金额
  "product_name" => 'product_name', //商品名称
);

#变更参数设置
if (strstr($pay_type, "银联钱包")) {
  $scan = 'yl';
  $data['service_type'] = 'unionpay_h5api';
  $data['interface_version'] = 'V3.1';//接口版本
  $form_url = 'https://api.vsdpay.com/gateway/api/scanpay';//扫码提交地址
} else {
  $scan = 'wy';
  $data['service_type'] = 'direct_pay';
  $data['input_charset'] = 'UTF-8';
  $data['return_url'] = $return_url;
  $data['interface_version'] = 'V3.0';//接口版本
  $data['pay_type'] = 'b2c';//支付类型
  $form_url = 'https://pay.vsdpay.com/gateway?input_charset=UTF-8';//网银提交地址
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
$noarr = array('sign', 'sign_type');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1);

$merchant_private_key = openssl_get_privatekey($private_pem);
if (!$merchant_private_key) {
  echo '打开私钥失败';
  exit;
}
openssl_sign($signtext, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
$sign = base64_encode($sign_info);

$data['sign'] = $sign;
$data_str = http_build_query($data);

if ($scan == 'yl') {
#curl获取响应值
  $res = curl_post($form_url, $data_str);
  $xml = (array)simplexml_load_string($res) or die("Error: Cannot create object");
  $row = json_decode(json_encode($xml), 1);

#跳转
  if ($row["response"]['resp_code'] != 'SUCCESS') {
    echo '处理码:' . $row["response"]['resp_code'] . "<br>";
    echo '处理描述信息:' . $row["response"]['resp_desc'] . "<br>";
    exit;
  } else if ($row["response"]['result_code'] != '0') {
    echo '业务结果:' . $row["response"]['result_code'] . "<br>";
    echo '错误码定义:' . $row["response"]['error_code'] . "<br>";
    echo '交易说明:' . $row["response"]['result_desc'] . "<br>";
    exit;
  } else {
    if (_is_mobile()) {
      $jumpurl = urldecode($row['response']['payURL']);
    } else {
      $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($row['response']['qrcode']);
    }
  }

#跳轉方法
  header("location:" . $jumpurl);
  exit;
}
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
  <form method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
     <p>正在为您跳转中，请稍候......</p>
       <?php foreach ($data as $arr_key => $arr_value) { ?>
         <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
       <?php 
    } ?>
   </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>