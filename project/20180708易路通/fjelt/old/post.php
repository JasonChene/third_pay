<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}


#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//appid
$pay_mkey = $row['mer_key'];//key
$pay_account = $row['mer_account'];//session
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
$form_url ='http://bank.fjelt.com/pay/Rest';

#第三方参数设置
$parms = array(
  'amount' => (int)$mymoney*100,
  'payordernumber' => $order_no,
  'backurl' => $merchant_url,
  'body' => "pay",
  'PayType' => "",
  'SubpayType' => "",
);
$data =array(
  'appid' => $pay_mid,
  'method' => "masget.pay.compay.router.font.pay",
  'format' => "json",
  'data' => "",
  'v' => "2.0",
  'timestamp' => date("Y-m-d H:m:s",time()),
  'session' => $pay_account,
  'sign' => "",
);
#变更参数设置
if (strstr($_REQUEST['pay_type'], "银联钱包")) {
  $scan = 'yl';
  $payType = $pay_type."_yl";
  $bankname = $pay_type . "->银联钱包在线充值";
  $parms['PayType'] = '0';
  $parms['SubpayType'] = '03';
}elseif (strstr($_REQUEST['pay_type'], "银联快捷")) {
  $scan = 'ylkj';
  $payType = $pay_type."_ylkj";
  $bankname = $pay_type . "->银联快捷在线充值";
  $parms['PayType'] = '0';
  $parms['SubpayType'] = '02';
}else {
  $scan = 'wy';
  $payType = $pay_type."_wy";
  $bankname = $pay_type . "->网银在线充值";
  $parms['PayType'] = '0';
  $parms['SubpayType'] = '01';
}
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
$parms_str = json_encode($parms);
$aes_parms_str = openssl_encrypt($parms_str,"AES-128-CBC",$pay_mkey,OPENSSL_RAW_DATA,$pay_mkey);
$aes_parms_str2 = base64_encode($aes_parms_str);
$data['data'] = str_replace(array('+','/'),array('-','_'),$aes_parms_str2);

$data['sign'] = strtolower(md5($pay_mkey.$data['appid'].$data['data'].$data['format'].$data['method'].$data['session'].$data['timestamp'].$data['v'].$pay_mkey));

$postdata = http_build_query($data);
$options = array( 'http' => array( 'method' => 'POST','header' =>'Content-type:application/x-www-form-urlencoded','content' => $postdata,'timeout' =>  60 // 超时时间（单位:s）    
	)  );
$context = stream_context_create($options);
$result = file_get_contents($form_url, false, $context);
$json=json_decode($result);
if($json->ret!='0')          
  echo $json->message;
else          
  header("Location:".$json->data);
?>
