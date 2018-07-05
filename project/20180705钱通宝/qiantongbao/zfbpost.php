<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function QRcodeUrl($code){
  $code2=str_replace("&", "aabbcc", $code);
  return $code2;
}

function curl_post($url,$data)
{
  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
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
      echo(curl_errno($ch));
      return curl_error($ch);
    }
    curl_close($ch);
    return $tmpInfo;
}

$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['zfb_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['zfb_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
$form_url = 'http://61.160.215.66:8093/bank/index.aspx';  //提交地址
$parter = $pay_mid;  //商戶號
$src_code=$pay_account;//商户标识
$value1 = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$orderid = getOrderNo();  //随机生成商户订单编号
$callbackurl = $merchant_url;  //异步
 $hrefbackurl = $return_url;//同步
// H5選擇type
if (_is_mobile()) {
  $type = '1006';//支付宝 WAP
} else {
  $type = '1003';//支付宝扫码 
}

$parms = array(
  "parter" => $parter,//商户号
  "type" => $type,//支付类型
  "value" => $value1,//金额
  "orderid" => $orderid,//订单号
  "callbackurl"=> $callbackurl ,//异步地址 
);
$signtext='';
$data='';
foreach ($parms as $key => $value) {
  $signtext .= $key . '=' . $value . '&';
  $data .= $key . '=' . $value . '&';
}
$signtext=substr($signtext,0,-1).$pay_mkey;
$sign = strtolower(md5($signtext));
$data =$data."sign=".$sign;

$bankname = $pay_type . "->支付宝在线充值";
$payT = $pay_type . "_zfb";

$result_insert = insert_online_order($_REQUEST['S_Name'], $orderid, $value1, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
$form_url2=mb_convert_encoding($form_url, "GB2312","UTF-8");//UTF-8转码GB2312
$data2=mb_convert_encoding($data, "GB2312","UTF-8");
if(_is_mobile()){
  header("location:".$form_url2."?".$data2);
}else{
  $res=curl_post($form_url2,$data2);
  $res2=mb_convert_encoding($res, "UTF-8","GB2312");//GB2312转码UTF-8
  $resp=json_decode($res2,1);
  if($resp['retCode']== "0000"){
    $qrurl=QRcodeUrl($resp['codeUrl']);
    header("location:".'../qrcode/qrcode.php?type=zfb&code='.$qrurl );
  }else{
    if(isset($resp['retMsg'])){
      echo $resp['retMsg'];
    }else{
      echo $res2;
    }
  }
}


?>
