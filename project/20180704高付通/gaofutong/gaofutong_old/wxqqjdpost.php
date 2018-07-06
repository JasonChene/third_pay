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
}

function device_type()
{
  $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
  $phone_type = 'other';
  if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
    $phone_type = '02';
  } elseif (strpos($agent, 'android')) {
    $phone_type = '01';
  }
  return $phone_type;
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
  "merchantNo" => $pay_mid, //商户编号
  "orderAmount" => $_REQUEST['MOAmount'] * 100, //商户订单金额
  "orderNo" => $order_no, //商户订单号
  "notifyUrl" => $merchant_url, //异步通知
  "callbackUrl" => $return_url, //页面回调
  "payType" => '', //支付方式
  "productName" => '', //商品名称
  "productDesc" => '', //商品描述
  "remark" => '', //备注
  "ip" => '', //用户的ip地址

  "accountName" => '', //持卡人姓名
  "accountNo" => '', //银行卡号
  "idCardNo" => '', //身份证号
  "phone" => '', //手机号
  "openid" => '', //微信公众号支付时必传

  "settleType" => '', //结算
  "payTypeConfig" => '', //到账时间
  "deviceType" => '', //手机系统
  "mchAppId" => 'mchAppId', //应用唯一标识
  "mchAppName" => 'mchAppName', //应用名称
  "sign" => '', //签名
);

#变更参数设置
$form_url = 'https://pay.166985.com/wappay/payapi/order';

if (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $data['payType'] = '14';//京东扫码
  $bankname = $pay_type . "->京东钱包在线充值";
  $payType = $pay_type . "_jd";
} elseif (strstr($pay_type, "QQ钱包") || strstr($pay_type, "qq钱包")) {
  $scan = 'qq';
  $data['payType'] = '8';//QQ扫码
  if (_is_mobile()) {
    $data['payType'] = '7';//QQ H5
    $data['deviceType'] = device_type();
  }
  $bankname = $pay_type . "->QQ钱包在线充值";
  $payType = $pay_type . "_qq";
} else {
  $scan = 'wx';
  $data['payType'] = '2';//微信扫码 17微信刷卡
  if (_is_mobile()) {
    $data['payType'] = '3';//微信H5 12微信app
    $data['deviceType'] = device_type();
  }
  $bankname = $pay_type . "->微信在线充值";
  $payType = $pay_type . "_wx";
}

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
$signtext = substr($signtext, 0, -1) . $pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;
$data_str = http_build_query($data);

#curl获取响应值
$res = curl_post($form_url, $data_str);
$tran = mb_convert_encoding("$res", "UTF-8");
$row = json_decode($tran, 1);

#跳转
if ($row['status'] != 'T') {
  echo '错误代码:' . $row['errCode'] . "\n";
  echo '错误讯息:' . $row['errMsg'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['payUrl'];
  if (!_is_mobile() || $scan == 'jd') {
    if (strstr($qrcodeUrl, "&")) {
      $code = str_replace("&", "aabbcc", $qrcodeUrl);//有&换成aabbcc
    } else {
      $code = $qrcodeUrl;
    }
    $jumpurl = ('../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
  } else {
    $jumpurl = $qrcodeUrl;
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

