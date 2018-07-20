<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");


$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function curl_post($url, $data, $timeout = 30)
{ #POST访问
  $ch = curl_init();
  curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // 让cURL自己判断使用哪个版本
  curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 在HTTP请求中包含一个"User-Agent: "头的字符串。
  curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);					// 在发起连接前等待的时间，如果设置为0，则无限等待
  curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);							// 设置cURL允许执行的最长秒数
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// 返回原生的（Raw）输出
  curl_setopt($curl, CURLOPT_ENCODING, FALSE);							// HTTP请求头中"Accept-Encoding: "的值。支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，请求头会发送所有支持的编码类型。
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  $jsonStr = json_encode($data);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json; charset=utf-8',
  'Content-Length: ' . strlen($jsonStr),
  ));
  curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonStr);
	curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
	$response=curl_exec($ch);
  curl_close($ch);
  if (curl_errno($ch)) {
    return curl_error($ch).'and'.curl_errno($ch);
  }
  return $response;
}

function encrypt_sign($md5Sign,$priKey){
  $priKey = "-----BEGIN RSA PRIVATE KEY-----\n" .wordwrap($priKey, 64, "\n", true) ."\n-----END RSA PRIVATE KEY-----";
  $priKey = openssl_get_privatekey($priKey);
  openssl_sign($md5Sign, $sign, $priKey, 'SHA256');
  $encrypt_sign = base64_encode($sign);
  return $encrypt_sign;
}


$scan = 'wx';
if (strstr($pay_type, "QQ钱包") || strstr($pay_type, "qq钱包")) {
  $scan = 'qq';
}


//获取第三方资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$idTemp = $row['mer_account'];
$idArray = explode("###", $idTemp);
$md5key =$idArray[0];
$agtId =$idArray[1];//商户机构号
$public_key = $idArray[2];//商户公钥
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


$form_url = 'http://pay.d1qp.cn:18098/webwt/pay/gateway.do';  //提交地址
$tranCode ='1101';//交易码
$agtId = $agtId;//机构号
$merId = $pay_mid;  //商戶号
$orderAmt = number_format($_REQUEST['MOAmount']*100, 0, '', ''); //订单金额 单位分
$orderId = getOrderNo();  //随机生成商户订单编号
$goodsName = "1234567890";//商品描述
$notifyUrl = $merchant_url;  //异步
$nonceStr = '1234567890';//随机字符串
$termIp = getClientIp();
if ($scan == 'wx') {
  $stlType = 'T1';//结算类型 支付宝，微信是T1  银联扫码和QQT0
  if(_is_mobile()){
    $payChannel = 'WXWAPPAY'; //微信H5
  }else{
    $payChannel = 'WXPAY'; //微信支付
  }
}elseif ($scan == 'qq') {
  $stlType = 'T0';
  $payChannel = 'QQPAY'; //QQ钱包
}

$parms = array(
  "tranCode" => $tranCode,
  "agtId" => $agtId,
  "merId" => $merId,
  "orderAmt" => $orderAmt,
  "orderId" => $orderId,
  "goodsName" => $goodsName,
  "notifyUrl" => $notifyUrl,
  "nonceStr" => $nonceStr,
  "stlType" => $stlType,
  "termIp" => $termIp,
  "payChannel" => $payChannel
);
ksort($parms);
$noarr =array('sign');
$signText = '';
$data = '';
foreach ($parms as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signText .= $arr_key.'='.$arr_val.'&';
	}
}

$signText = $signText.'key='.$md5key;
$md5Sign = strtoupper(md5($signText));
$sign = encrypt_sign($md5Sign,$pay_mkey);
$data = array(
    'REQ_HEAD' => array('sign'=>$sign),
    'REQ_BODY' => $parms
);

if ($scan == 'wx') {
  $payType = $pay_type."_wx";
  $bankname = $pay_type . "->微信在线充值";
}elseif ($scan == 'qq') {
  $payType = $pay_type."_qq";
  $bankname = $pay_type . "->QQ钱包在线充值";
}
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
//確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderId, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

$res = curl_post($form_url,$data,20);
$row = json_decode($res);
if ($row->REP_BODY->orderState == '00') {
  if (_is_mobile() && $scan == 'wx') {
    header("location:" . $row->REP_BODY->codeUrl);
    exit;
  }else {
    header("location:" .'../qrcode/qrcode.php?type='.$scan.'&code=' .$row->REP_BODY->codeUrl);
    exit;
  }
}else {
  echo  'rspcode:' . $row->REP_BODY->rspcode.'rspmsg:' . $row->REP_BODY->rspmsg;
}

?>
