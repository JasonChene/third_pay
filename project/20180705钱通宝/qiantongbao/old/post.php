<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.config.php");
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
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wy_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wy_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$ylkjscan = false;//手机快捷银行
if (strstr($_REQUEST['pay_type'], "银联快捷") && _is_mobile() )
{
    $ylkjscan = true;
}

$form_url = 'http://pay.miliqp.com/bank/index.aspx';  //提交地址
$parter = $pay_mid;  //商戶號
$src_code=$pay_account;//商户标识
$value = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$orderid = getOrderNo();  //随机生成商户订单编号
$callbackurl = $merchant_url;  //异步
 $hrefbackurl = $return_url;//同步

// H5選擇type
 if($ylkjscan){
  $type="1005";//手机快捷银行
 }else{
  $type=$_REQUEST['bank_code'];//网银
 }


$parms = array(
  "parter" => $parter,//商户号
  "type" => $type,//支付类型
  "value" => $value,//金额
  "orderid" => $orderid,//订单号
  "callbackurl"=> $callbackurl ,//异步地址 
);
$signtext='';
$data='';
foreach ($parms as $arr_key => $arr_value) {
  $signtext .= $arr_key . '=' . $arr_value . '&';
  $data .= $arr_key . '=' . $arr_value . '&';
}
$signtext=substr($signtext,0,-1).$pay_mkey;
$sign = strtolower(md5($signtext));
$data =$data."sign=".$sign;
if($ylkjscan){
  $payT = $pay_type."_ylkj";
  $bankname = $pay_type."->银联快捷在线充值";
}else{
  $bankname = $pay_type."->网银在线充值";
  $payT = $pay_type."_wy";
}


$result_insert = insert_online_order($_REQUEST['S_Name'], $orderid, $value, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
$form_url2=mb_convert_encoding($form_url, "GB2312","UTF-8");//UTF-8转码GB2312
$data2=mb_convert_encoding($data, "GB2312","UTF-8");
header("location:".$form_url2."?".$data2);

?>
