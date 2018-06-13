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
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
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

#第三方参数设置
$data = array(
  "service" => 'pay.alipay.codepay', //接口类型
  "version" => '1.0', //版本号
  "charset" => 'UTF-8', //字符集
  "sign_type" => 'MD5', //签名方式
  "merchant_id" => $pay_mid, //商户号
  "out_trade_no" => $order_no, //商户订单号
  "goods_desc" => 'teddy', //商品描述
  "attach" => 'attach', //附加信息
  "total_amount" => $mymoney = number_format($_REQUEST['MOAmount'], 2, '.', ''), //总金额
  "notify_url" => $merchant_url, //通知地址
  "return_url" => $return_url, //前台地址
  "nonce_str" => time(), //随机字符串
  "sign" => '' //签名
);

#变更参数设置
$form_url = 'http://106.15.159.189/smartpayment/pay/gateway';//提交地址
$scan = 'zfb';
$data['service'] = 'pay.alipay.codepay';
if (_is_mobile()) {
  $data['service'] = 'pay.alipay.wappay';
}
$bankname = $pay_type . "->支付宝在线充值";
$payType = $pay_type . "_zfb";

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
$data_str = http_build_query($data);

#curl获取响应值
$res = curl_post($form_url, $data_str);
$tran = mb_convert_encoding("$res", "UTF-8", "auto");
$row = json_decode($tran, 1);

//打印
// echo '<pre>';
// echo ('<br> data = <br>');
// var_dump($data);
// echo ('<br> signtext = <br>');
// echo ($signtext);
// echo ('<br><br> row = <br>');
// var_dump($row);
// echo '</pre>';

// exit;

#跳转
if ($row['status'] != '0') {
  echo '返回状态码:' . $row['status'] . "\n";//返回状态码
  echo '返回信息:' . $row['message'] . "\n";//返回信息
  exit;
} elseif ($row['result_code'] != '0') {
  echo '业务结果:' . $row['result_code'] . "\n";//业务结果
  echo '错误代码 描述:' . $row['err_code'] . ' ' . $row['err_msg'] . "\n";//错误代码 . 错误代码描述
  exit;
} else {
  if (!_is_mobile()) {
    $jumpurl = $row['pay_info'];
  } else {
    $jumpurl = $row['pay_info'];
  }
}

#跳轉方法

echo '正在为您跳转中，请稍候......';
header('Location:' . $jumpurl);
exit;
?>


