<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

function curl_post($url,$data){
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url);
  //设置请求头
  $header=array(  
    "Accept: application/json",  
    "Content-Type: application/json;charset=utf-8",  
  ); 
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 	  
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
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
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key']; //商戶公鑰
$pay_account = $row['mer_account']; //商戶私鑰
$return_url = $row['pay_domain'] . $row['wy_returnUrl'];//return跳轉地址
$merchant_url = $row['pay_domain'] . $row['wy_synUrl'];//notify回傳地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

// 金木支付參數設定
$form_url = "https://pay.papayaer.com/pay"; // 提交地址
$cid = $pay_mid; // 渠道号
$total_fee = number_format($_REQUEST['MOAmount'] * 100, 2, '.', ''); // 訂單支付金額,小數點兩位
$title = 'test'; // 标题描述
$attach = 'test'; // 自定义参数
$platform = 'CR_QQ'; // QQ扫码
$cburl = $return_url; // 支付成功后跳转页面
$orderno = getOrderNo(); // 商户订单号
$token_url = $merchant_url; // 服务器异步通知URL

// QQ参数设定
$payType = $pay_type."_qq";
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$bankname = $pay_type . "->QQ钱包在线充值";
if ( _is_mobile() ) {
	$platform = 'TEN_CR'; // QQ钱包
}

// signText Array
$parms = array(
	'attach'=>$attach,
	'cburl'=>$cburl,
	'cid'=>$cid,
	'orderno'=>$orderno,
	'platform'=>$platform,
	'title'=>$title,
	'token_url'=>$token_url,
	'total_fee'=>$total_fee
);
$signText = '';
foreach ($parms as $key => $val) {
  $signText .= $val;
}
$signText .= $pay_mkey;
$sign = strtoupper(MD5($signText));
$parms['sign'] = $sign;
$data = json_encode($parms);

//確認訂單有無重複， function在 moneyfunc.php 裡
//insert_online_order($_REQUEST['S_Name'],訂單編號,支付金額,$bankname, $payType, $top_uid)
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderno, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

$return = curl_post($form_url, $data);
$row = json_decode($return, true);
if ( $row && $row['err'] == "200" ) {
  if ( _is_mobile() ) {
    header('location:' . $row['code_url']);
  }else{
    header('location:' . $row['code_img_url']);
  }
}else{
  echo "支付失败";
}
?>
