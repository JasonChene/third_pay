<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

// #function
function http_poststr($url, $data_string)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charset=utf-8',
    'Content-Length: ' . strlen($data_string)
  ));
  ob_start();
  curl_exec($ch);
  $return_content = ob_get_contents();
  ob_end_clean();

  $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  return $return_content;
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
  "seller_id" => $pay_mid, //商户ID
  "order_type" => '', //订单类型
  "pay_body" => 'paybody', //商品描述
  "out_trade_no" => $order_no, //订单号
  "total_fee" => number_format($_REQUEST['MOAmount'] * 100, 0, '.', ''), //订单金额
  "notify_url" => $merchant_url, //回调地址
  "return_url" => $return_url, //回跳地址
  "spbill_create_ip" => getClientIp(), //订单创建ip
  "spbill_times" => date("YmdHis"), //时间戳
  "noncestr" => substr((string)rand(0, pow(10, 8) - 1) + pow(10, 8), 1), //随机字符串
  "remark" => 'remark', //订单备注
  "sign" => '', //签名
);

#变更参数设置
$form_url = 'http://api.qianxiyida.com/ecpay/xbdo';//提交地址
$scan = 'wx';
$data['order_type'] = '2701';
if (_is_mobile()) {
  $data['order_type'] = '2706';
}
$bankname = $pay_type . "->微信在线充值";
$payType = $pay_type . "_wx";


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
$signtext = substr($signtext, 0, -1);

#RSA-S签名
$private_key = openssl_get_privatekey($pay_mkey);
if ($private_key == false) {
  echo "打开密钥失败";
  exit;
}
openssl_sign($signtext, $opensslsign, $private_key, OPENSSL_ALGO_MD5);
$data['sign'] = base64_encode($opensslsign);

#curl获取响应值
$res = http_poststr("$form_url", base64_encode(json_encode($data)));
$tran = mb_convert_encoding("$res", "UTF-8");
$row = json_decode($tran, 1);

#跳转
if ($row['state'] != '00') {
  echo '错误代码:' . $row['return_code'] . "\n";
  echo '错误讯息:' . $row['return_msg'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['pay_url'];
  if (!_is_mobile()) {
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

