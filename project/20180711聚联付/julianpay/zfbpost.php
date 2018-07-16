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
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
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
  "pay_memberid" => $pay_mid, //商户ID
  "pay_orderid" => $order_no, //订单号
  "pay_amount" => number_format($_REQUEST['MOAmount'], 0, '.', ''), //金额
  "pay_applydate" => date("Y-m-d H:i:s"), //订单提交时间
  "pay_bankcode" => '', //银行编号
  "pay_notifyurl" => $merchant_url, //服务端返回地址
  "pay_callbackurl" => $return_url, //页面返回地址
  "pay_md5sign" => '', //MD5签名字段
);

#变更参数设置
$form_url = 'http://www.julianfu.com/Pay_Index.html';//扫码提交地址
$scan = 'zfb';
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
$noarr = array('pay_reserved1', 'pay_reserved2', 'pay_reserved3', 'pay_productname', 'pay_productnum', 'pay_productdesc', 'pay_producturl', 'pay_md5sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (isset($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=>' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1) . '&key=' . $pay_mkey;
$sign = strtoupper(md5($signtext));
$data['pay_md5sign'] = $sign;
$data_str = http_build_query($data);

#curl获取响应值
$res = curl_post($form_url, $data_str);
$row = json_decode($res, 1);

#跳转
if ($row['returncode'] != '00') {
  echo '错误代码:' . $row['returncode'] . "\n";
  echo '错误讯息:' . $row['msg'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['qrcode'];
  $jumpurl = $qrcodeUrl;
}

#跳轉方法
header("Location: $jumpurl");
exit;
?>


