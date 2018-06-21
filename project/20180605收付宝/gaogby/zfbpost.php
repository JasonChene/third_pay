<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

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
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶密钥
$pay_account = $row['mer_account'];//商户支付Key
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
$form_url = 'https://gateway.gaogby.com/scanPay/initPay';
#第三方参数设置
$data = array(
  'payKey' => $pay_account,//商户支付Key
  'orderPrice' => $mymoney,//订单金额，单位：元,保留小数点后两位
  'outTradeNo' => $order_no,//商户支付订单号
  'productType' => "",//支付方式编码
  'orderTime' => date('YmsHis'),//下单时间，格式(yyyyMMddHHmmss)
  'productName' => "pay",//支付产品名称
  'orderIp' => getClientIp(),//下单IP
  'returnUrl' => $return_url,//页面通知地址
  'notifyUrl' => $merchant_url,//后台异步通知地址
  'sign' => ''//MD5大写签名
);
#变更参数设置
$scan = 'zfb';
$payType = $pay_type . "_zfb";
$bankname = $pay_type . "->支付宝在线充值";
$data['productType'] = "20000301";//支付宝掃碼
if (_is_mobile()) {
  $data['productType'] = "20000201";//手机支付宝T0 20000203 T1 20000201
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
$noarr = array('sign');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}

$signtext = substr($signtext, 0, -1) . '&paySecret=' . $pay_mkey;
$data['sign'] = strtoupper(md5($signtext));

#curl获取响应值
$res = curl_post($form_url, $data);
$row = json_decode($res, 1);

#跳转
if ($row['resultCode'] != '0000') {
  echo '错误代码:' . $row['resultCode'] . "<br>";
  echo '错误讯息:' . $row['errMsg'] . "<br>";
  echo '<pre>';
  echo '请求报文：<br>';
  var_dump($data);
  echo '响应报文：<br>';
  var_dump($res);
  echo '响应报文阵列：<br>';
  var_dump($row);
  exit;
} else {
  if (!_is_mobile()) {
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($row['payMessage']);
  } else {
    $jumpurl = $row['payMessage'];
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
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>