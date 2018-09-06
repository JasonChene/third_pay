<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#function
function curl_post($url, $data, $str)
{ #curl请求设定
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  #curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
  if (strstr($str, "POST")) {
    if (strstr($str, "JSON")) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
      ));
    }
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  } elseif (strstr($str, "GET")) {
    if (strstr($str, "JSON")) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
      ));
    }
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    // $post_url=fix_postdata_url($url, $data);
    curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
  }
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    echo (curl_errno($ch));
    exit;
  }
  curl_close($ch);
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
{ #替换QRcodeUrl中&符号
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
}

function toXml($arr)
{ #拼装Xml
  $xml = "<xml>";
  foreach ($arr as $key => $val) {
    if (is_numeric($val)) {
      $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
    } else {
      $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
    }
  }
  $xml .= "</xml>";
  return $xml;
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
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
#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  "mch_id" => $pay_mid, //商户号
  "out_trade_no" => $order_no, //商户订单号
  "body" => 'body', //商品描述
  "total_fee" => number_format($_REQUEST['MOAmount'] * 100, 0, '.', ''), //总金额
  "mch_create_ip" => getClientIp(), //终端IP
  "notify_url" => $merchant_url, //通知地址
  "nonce_str" => mt_rand(), //随机字符串
  "sign" => '', //签名
);

#变更参数设置
if (strstr($pay_type, "银联快捷")) {
  $form_url = 'http://www.yljdgl.cn/api/sig/v1/union/quick';//银联快捷提交地址
  $scan = 'ylkj';
} else {
  $form_url = 'http://www.yljdgl.cn/api/sig/v1/union/net';//银联网关提交地址
  $scan = 'wy';
  $data['bank_id'] = $_REQUEST['bank_code'];
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
$noarr = array('sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1) . '&key=' . $pay_mkey;
$sign = strtoupper(md5($signtext));
$data['sign'] = $sign;
$data_str = toXml($data);

#获取token
$token_data = array(
  "appid" => $pay_mid, //应用ID
  "random" => mt_rand(), //随机字符串
  "sign" => '', //签名
);
$token_url = 'http://www.yljdgl.cn/api/auth/access-token';//获取token提交地址
$token_signtext = $token_data['appid'] . $pay_mkey . $token_data['random'];
$token_sign = md5($token_signtext);
$token_data['sign'] = $token_sign;
$res = curl_post($token_url, http_build_query($token_data), "GET");
$xml = (array)simplexml_load_string($res) or die("Error: Cannot create object");
$row = json_decode(json_encode($xml), 1);//XML回传资料

echo '<pre>';
echo ('<br> data = <br>');
var_dump($data);
echo ('<br> signtext = <br>');
echo ($signtext);
echo ('<br><br> row = <br>');
var_dump($row);

echo '------';

echo '</pre>';

#curl获取响应值
$res = curl_post($form_url . '?' . $row['token'], $data_str, "POST");
$xml = (array)simplexml_load_string($res) or die("Error: Cannot create object");
$row = json_decode(json_encode($xml), 1);//XML回传资料

//打印
echo '<pre>';
echo ('<br> data = <br>');
var_dump($data);
echo ('<br> signtext = <br>');
echo ($signtext);
echo ('<br><br> row = <br>');
var_dump($row);
echo '</pre>';

exit;

#跳转
if ($row['respCode'] != '0000') {
  echo '错误代码:' . $row['respCode'] . "\n";
  echo '错误讯息:' . $row['respInfo'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['qrcodeUrl'];
  if (_is_mobile()) {
    $jumpurl = $qrcodeUrl;
  } else {
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($qrcodeUrl);
  }
}

#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

