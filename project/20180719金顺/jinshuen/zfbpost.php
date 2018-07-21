<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}
#function
function Tra_data($array){//传输资料转格式
    $xml ='<?xml version="1.0" encoding="utf-8" standalone="no"?><message ';
    foreach ($array as $arr_key=>$arr_value)
    {
      $xml.=$arr_key.'="'.$arr_value.'" ';
    }
    $xml = substr($xml,0,-1);
    $xml.="/>";
    return $xml;
  
}
function sign($key,$data) {
	// $private_pem = chunk_split($key,64,"\r\n");//转换为pem格式的钥
  // $private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";
  $private_pem = openssl_get_privatekey($key);//签名秘钥
	$signature = '';  
	openssl_sign($data, $signature, $private_pem);
	return base64_encode($signature);
} 
function verity($key,$data,$signature)  
{  
  $public_pem = openssl_get_publickey($key);//签名秘钥
	$result = (bool)openssl_verify($data, base64_decode($signature), $public_pem);  
	return $result;  
}
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
}
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}
function payType_bankname($scan,$pay_type){
  global $payType, $bankname;
  if(strstr($scan,"wy")){
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  }elseif(strstr($scan,"yl")){
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  }elseif(strstr($scan,"qq")){
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  }elseif(strstr($scan,"wx")){
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  }elseif(strstr($scan,"zfb")){
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  }elseif(strstr($scan,"jd")){
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  }elseif(strstr($scan,"ylkj")){
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  }elseif(strstr($scan,"bd")){
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  }
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
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
$form_url = 'http://js.011vip.cn:9090/jspay/payGateway.htm';//接入提交地址

#第三方参数设置
$data = array(
  "application" => '',//应用名称
  "version" => '1.0.1',//通讯协议版本号
  "timestamp" => date("YmdHis"),//时间戳
  "merchantId" => $pay_mid, //商户代码
  "merchantOrderId" => $order_no, //商户订单号
  "merchantOrderAmt" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''), //金额
  "merchantOrderDesc" => 'iPhone6S', //订单描述
  "userName" => $_REQUEST['S_Name'],//用户名
  "merchantPayNotifyUrl" => $merchant_url, //下行异步通知地址
  "payerId" => '',
  "salerId" => '',
  "guaranteeAmt" => ''
);

#变更参数设置
$scan = 'zfb';
  $data['application'] = 'ZFBScanOrder';
  if (_is_mobile()) {
    $data['application'] = 'ZFBWAPOrder';
  }
payType_bankname($scan,$pay_type);

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
$signtext = Tra_data($data);
$newsigntext = MD5($signtext,1);
$sign = sign($pay_mkey,$newsigntext);
$postdata = base64_encode($signtext)."|".$sign;
$res = curl_post($form_url,$postdata);
//返回值处理
$rep0 = explode('|',$res);
$rep = base64_decode($rep0[0]);
$rep1 = explode('<',$rep);
$rep2 = explode('>',$rep1[2]);
$rep3 = substr($rep2[0],0,-1);
$newreparr = explode(' ',$rep3);
$respone = array();
foreach($newreparr as $reparr_key => $reparr_value){
  $newdata = explode('=',$reparr_value);
  $respone[$newdata[0]] = substr($newdata[1],1,-1);
}
//返回值处理

if($respone['respCode'] != '000'){
  echo  '错误代码:' . $respone['respCode']."\n<br>";
  echo  '错误讯息:' . $respone['respDesc']."\n<br>";
  exit;
}else{
  if(_is_mobile()){
    $jumpurl = $respone['codeUrl'];
  }else{
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' .QRcodeUrl($respone['codeUrl']);
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
  <form method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
     <p>正在为您跳转中，请稍候......</p>

   </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

