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
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
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
$array = array(
    '红木餐桌','圆桌','转盘','花梨木','刺猬','紫檀中式实木','雕花圆台','餐桌椅组合',
    '王木匠','鸡翅','木餐桌椅一桌','四凳小户型圆形餐桌','实木红木家具简约圆桌'
);
#第三方参数设置
$data = array(
  //基本参数
  "merchant_code" => $pay_mid, //商家号
  "service_type" => 'weixin_scan', //业务类型
  "notify_url" => $merchant_url, //服务器异步通知地址
  "interface_version" => 'V3.1', //接口版本
  "client_ip" => getClientIp(), //客户端IP
  "sign_type" => 'RSA-S', //签名方式
  // "sign" => '', //签名

  //业务参数
  "order_no" => $order_no, //商户网站唯一订单号
  "order_time" => date("Y-m-d H:i:s"), //商户订单时间
  "order_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //商户订单总金额
  "product_name" => $array[rand(0,12)], //商品名称
  // "product_code" => '', //商品编号
  // "product_num" => '', //商品数量
  // "product_desc" => '', //商品描述
  // "extra_return_param" => '', //公用回传参数
  // "extend_param" => '', //公用业务扩展参数
);

#变更参数设置
if (strstr($pay_type, "微信反扫")) {
  $scan = 'wxfs';
  $form_url = 'https://api.zdfmf.com/gateway/api/micropay';//条码提交地址
  $data['service_type'] = 'weixin_micropay';
  $data['interface_version'] = '3.0';
  $bankname = $pay_type . "->微信在线充值";
  $payType = $pay_type . "_wx";
} elseif (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $form_url = 'https://api.zdfmf.com/gateway/api/scanpay';//扫码提交地址
  $data['service_type'] = 'jdpay_scan';
  $bankname = $pay_type . "->京东钱包在线充值";
  $payType = $pay_type . "_jd";
} else {
  $scan = 'wx';
  $form_url = 'https://api.zdfmf.com/gateway/api/scanpay';//扫码提交地址
  $data['service_type'] = 'weixin_scan';
  if (_is_mobile()) {
    $form_url = 'https://api.zdfmf.com/gateway/api/h5apipay';//H5提交地址
    $data['service_type'] = 'weixin_h5api';
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
$noarr = array('sign_type', 'sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1);

$merchant_private_key = openssl_get_privatekey($pay_mkey);
if (!$merchant_private_key) {
  echo '打开私钥失败';
  exit;
}
openssl_sign($signtext, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
$sign = base64_encode($sign_info);
$data['sign'] = $sign;

#curl获取响应值
$res = curl_post($form_url, http_build_query($data));
$xml = (array)simplexml_load_string($res) or die("Error: Cannot create object");
$row = json_decode(json_encode($xml), 1);//XML回传资料

#跳转
if ($row['response']['resp_code'] != 'SUCCESS') {
  echo '错误代码:' . $row['response']['resp_code'] . "\n";
  echo '错误讯息:' . $row['response']['resp_desc'] . "\n";
  exit;
} elseif ($row['response']['result_code'] != '0') {
  echo '错误代码:' . $row['response']['result_code'] . "\n";
  echo '错误讯息:' . $row['response']['result_desc'] . "\n";
  exit;
} else {
  if (!_is_mobile() || $scan == 'jd' || $scan != 'wxfs') {
    if (strstr($row['response']['qrcode'], "&")) {
      $code = str_replace("&", "aabbcc", $row['response']['qrcode']);//有&换成aabbcc
    } else {
      $code = $row['response']['qrcode'];
    }
    $jumpurl = ('../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
  } else {
    $jumpurl = $row['response']['qrcode'];
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
<?php
} ?>
