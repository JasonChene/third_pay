<?php
header("Content-type:text/html; charset=utf-8");
#第三方名稱 : 鑫亿宝
#支付方式 : jd;
include_once("./addsign.php");
include_once("../moneyfunc.php");
include_once("../../../database/mysql.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

$S_Name = $_REQUEST['S_Name'];
$top_uid = $_REQUEST['top_uid'];
$pay_type = $_REQUEST['pay_type'];
#获取第三方资料(非必要不更动)
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//同步
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//异步
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


#固定参数设置
$form_url = 'https://www.xyb789.com/payGateway/payment/qrCode/v2';
$bank_code = $_REQUEST['bank_code'];
$order_no = getOrderNo();
$notify_url = $merchant_url;
$client_ip = getClientIp();
$pr_key = $pay_mkey;//私钥
$pu_key = $pay_account;//公钥
$order_time = date("YmdHis");
$orderExpireTime = date("YmdHis", strtotime("+10 minute"));
$ts = $order_time . substr((string)(rand(000, 999) + 1000), 1);

$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$MOAmount = number_format($_REQUEST['MOAmount'] * 100, 0, '.', '');
#第三方传值参数设置
$data = array(
  "mchntCode" => $pay_mid,
  "mchntOrderNo" => $order_no,
  "orderAmount" => $MOAmount,
  "notifyUrl" => $notify_url,
  "channelCode" => 'jd_wallet_qr',//京东钱包扫码
  "ts" => $ts,
  "clientIp" => $client_ip,
  "subject" => 'subject',
  "body" => 'body',
  "pageUrl" => $return_url,
  "orderTime" => $order_time,
  "orderExpireTime" => $orderExpireTime,
  "sign" => array(
    "str_arr" => array(
      "body" => "body",
      "channelCode" => 'jd_wallet_qr',//京东钱包扫码
      "clientIp" => $client_ip,
      "mchntCode" => $pay_mid,
      "mchntOrderNo" => $order_no,
      "notifyUrl" => $notify_url,
      "orderAmount" => $MOAmount,
      "orderExpireTime" => $orderExpireTime,
      "orderTime" => $order_time,
      "pageUrl" => $return_url,
      "subject" => "subject",
      "ts" => $ts,
    ),
    "mid_conn" => "=",
    "last_conn" => "&",
    "encrypt" => array(
      "0" => "MD5",
      "1" => "upper",
    ),
    "key_str" => "",
    "key" => $pr_key,
    "havekey" => "1",
  ),
);
#变更参数设定
$payType = $pay_type . "_jd";
$bankname = $pay_type . "->京东钱包在线充值";
#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($S_Name, $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}


#签名排列，可自行组字串或使用http_build_query($array)
foreach ($data as $arr_key => $arr_value) {
  if (is_array($arr_value)) {
    $data[$arr_key] = sign_text($arr_value);
  }
}
#curl获取响应值
$res = curl_post($form_url, json_encode($data, JSON_UNESCAPED_SLASHES), "JSON-POST");
$res = json_decode($res, 1);
#跳转qrcode
if ($res['retCode'] == '0000') {
  if (!empty($res['codeUrl'])) {
    //将该地址制作成二维码图片
    $qrurl = QRcodeUrl($res['codeUrl']);
    $jumpurl = '../qrcode/qrcode.php?type=zfb&code=' . $qrurl;
  } else if (!empty($res['imgSrc'])) {
    //已经生成好的二维码图片 直接展示出供客户扫码
    $jumpurl = $res['imgSrc'];
  } else {
    //二维码收银界面 直接跳转
    $jumpurl = $res['payUrl'];
  }
} else {
  echo "错误码：" . $res['retCode'] . "错误讯息：" . $res['retMsg'];
  exit();
}
echo '正在为您跳转中，请稍候......';
header('Location:' . $jumpurl);
exit();
?>
