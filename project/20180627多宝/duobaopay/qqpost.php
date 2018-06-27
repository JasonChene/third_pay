<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}


$top_uid = $_REQUEST['top_uid'];
//获取第三方资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_public_key = $row['mer_key'];//商户公钥
$pay_private_key = $row['mer_account'];//商戶私钥
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$notify_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_public_key == "") {
  echo "非法提交参数";
  exit;
}


$form_url = 'https://gwbb69.169.cc/interface/AutoBank/index.aspx';
if (_is_mobile()) {
  $type = '1102'; //QQ H5
} else {
  $type = '993'; //QQ bs
}
$value = number_format($_REQUEST['MOAmount'], 2, '.', '');//订单支付金额
$orderid = getOrderNo();//随机生成订单编号
$attach = $_REQUEST['S_Name'] . "|" . $dateis . "|" . md5($_REQUEST['S_Name'] . $pay_mid . $dateis);//商品信息

$parms = array(
  'parter' => $pay_mid,
  'type' => $type,
  'value' => $value,
  'orderid' => $orderid,
  'callbackurl' => $notify_url,
  'hrefbackurl' => $return_url,
  'attach' => $attach,
);

$noarr = array('attach', 'hrefbackurl');
$signText = '';
$data = '';
foreach ($parms as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signText .= $arr_key . '=' . $arr_val . '&';
  } else {
  }
  $data .= $arr_key . '=' . $arr_val . '&';
}
$signText = substr($signText, 0, -1);
$sign = mb_strtolower(md5($signText . $pay_public_key)); #生成签名
$data .= 'sign=' . $sign;

echo $signText . $pay_public_key . "<br><br>";
echo $data . "<br><br>";

$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$bankname = $pay_type . "->QQ钱包在线充值";
$payT = $pay_type . "_qq";
//确认订单有无重复，上传订单到k_money， function在 moneyfunc.php 里
//insert_online_order($_REQUEST['S_Name'],訂單編號,$mymoney,$bankname, $payT, $top_uid)
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderid, $mymoney, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

header("location:" . $form_url . '?' . $data);



?>
