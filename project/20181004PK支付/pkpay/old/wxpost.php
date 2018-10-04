<?php
header("Content-type:text/html; charset=utf-8");
include_once "../../../database/mysql.config.php";
include_once "../moneyfunc.php";

function curl_post($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //设置请求头
    $header = array(
        "Accept: application/json",
        "Content-Type: application/json;charset=utf-8",
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

$top_uid = $_REQUEST['top_uid'];
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Asia/Shanghai");
}

//獲取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key']; //商戶公鑰
$pay_account = $row['mer_account']; //商戶私鑰
$return_url = $row['pay_domain'] . $row['wx_returnUrl']; //return跳轉地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl']; //notify回傳地址
if ("" == $pay_mid || "" == $pay_mkey) {
    echo "非法提交参数";
    exit;
}

// PK支付參數設定
$form_url = "api.szcsjn.cn/grmApp/createScanOrder.do"; // 提交地址
$version = '1.0.0'; // 版本号
$merId = $pay_mid; // 商戶號
$merOrderNo = getOrderNo(); // 商户订单号
$orderAmt = number_format($_REQUEST['MOAmount'], 2, '.', ''); // 訂單支付金額,小數點兩位
$payPlat = 'wxpay'; //alipay:支付宝 wxpay:微信支付
$orderTitle = 'test'; // 订单标题
$orderDesc = 'test'; // 订单描述
$notifyUrl = $merchant_url; // 服务器异步通知URL
$callbackUrl = $return_url; // 支付成功后跳转页面

// 微信参数设定
$payType = $pay_type . "_wx";
$bankname = $pay_type . "->微信在线充值";
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
if (_is_mobile()) {
  $form_url = 'api.szcsjn.cn/grmApp/createWapOrder.do';
}

// signText Array
$parms = array(
    'version' => $version,
    'merId' => $merId,
    'merOrderNo' => $merOrderNo,
    'orderAmt' => $orderAmt,
    'payPlat' => $payPlat,
    'orderTitle' => $orderTitle,
    'orderDesc' => $orderDesc,
    'notifyUrl' => $notifyUrl,
    'callbackUrl' => $callbackUrl
);
ksort($parms);
$signText = '';
foreach ($parms as $key => $val) {
  $signText .= $key . "=" . $val . "&";
}
$signText .= "key=" . $pay_mkey;
$sign = MD5($signText);
$parms['sign'] = $sign;
ksort($parms);
$data = json_encode($parms);

//確認訂單有無重複， function在 moneyfunc.php 裡
//insert_online_order($_REQUEST['S_Name'],訂單編號,支付金額,$bankname, $payType, $top_uid)
$result_insert = insert_online_order($_REQUEST['S_Name'], $merOrderNo, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
    echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
    exit;
} else if ($result_insert == -2) {
    echo "订单号已存在，请返回支付页面重新支付";
    exit;
}

$return = curl_post($form_url, $data);
$row = json_decode($return, true);
if ($row && $row['respCode'] == "0000") {
  header('location:' . $row['jumpUrl']);
} else {
  echo $row['respMsg'];
}