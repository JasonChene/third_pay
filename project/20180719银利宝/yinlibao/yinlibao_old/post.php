<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init($url);
  $timeout = 6000;
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  
  //本地测试 不验证证书
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书

  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json; charset=utf-8",
    "Content-Length: " . strlen($data)
  ));
  $row = json_decode(curl_exec($ch), true);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $row;
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
$stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
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
  "trade_type" => '', //交易类型
  "nonce" => substr((string)rand(0, pow(10, 8) - 1) + pow(10, 8), 1), //随机字符串
  "user_id" => '', //用户ID
  "timestamp" => time(), //时间戳
  "subject" => 'tcejbus', //订单名称
  "detail" => '', //商品详情
  "out_trade_no" => $order_no, //商户订单号
  "total_fee" => number_format($_REQUEST['MOAmount'] * 100, 0, '.', ''), //总金额
  "spbill_create_ip" => getClientIp(), //终端IP
  "timeout" => '', //过期时长
  "notify_url" => $merchant_url, //异步地址
  "return_url" => $return_url, //返回地址
  "sign_type" => 'MD5', //签名类型
  "sign" => '', //签名信息
);

#变更参数设置
$form_url = 'https://api.365df.cn/pay/unifiedorder';//扫码提交地址
if (strstr($pay_type, "银联钱包")) {
  $scan = 'yl';
  $data['trade_type'] = 'UPSCAN';
} elseif (strstr($pay_type, "银联快捷")) {
  $scan = 'ylkj';
  $data['trade_type'] = 'QUICK';
} else {
  $scan = 'wy';
  $data['trade_type'] = 'GATEWAY';
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
$data_string = json_encode($data);

#curl获取响应值
$row = curl_post($form_url, $data_string);

#跳转
if (empty($row['pay_url'])) {
  echo '错误代码:' . $row['code'] . "\n";
  echo '错误讯息:' . $row['message'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['pay_url'];
  if (_is_mobile() || $scan != 'yl') {
    $jumpurl = $qrcodeUrl;
  } else {
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($qrcodeUrl);
  }
}

#跳轉方法
header("Location: $jumpurl");
?>

