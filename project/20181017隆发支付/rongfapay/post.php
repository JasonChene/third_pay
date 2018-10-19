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

function payType_bankname($scan, $pay_type)
{
  global $payType, $bankname;
  if ($scan == "wy") {
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  } elseif ($scan == "yl" || $scan == "ylfs") {
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  } elseif ($scan == "qq" || $scan == "qqfs") {
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  } elseif ($scan == "wx" || $scan == "wxfs") {
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  } elseif ($scan == "zfb" || $scan == "zfbfs") {
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  } elseif ($scan == "jd" || $scan == "jdfs") {
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  } elseif ($scan == "ylkj") {
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  } elseif ($scan == "bd" || $scan == "bdfs") {
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  } else {
    echo ('payType_bankname出错啦！');
    exit;
  }
}


#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
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
function QRcodeUrl($code)
{
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
  }
  return $code2;
}
#判断 php版本 编译成 json字符串
function jsonEncode($value){
	if (version_compare(PHP_VERSION,'5.4.0','<')){
		$str = json_encode($value);
		$str = preg_replace_callback("#\\\u([0-9a-f]{4})#i","replace_unicode_escape_sequence",$str);
		$str = stripslashes($str);
		return $str;
	}else{
		return json_encode($value,320);
	}
}
//加密
function encodePay($data,$pay_public_key){#加密//		
	$pu_key =  openssl_pkey_get_public($pay_public_key);
	if ($pu_key == false){
		echo "打开密钥出错";
		die;
	}
	$encryptData = '';
	$crypto = '';
	foreach (str_split($data, 117) as $chunk) {            
    openssl_public_encrypt($chunk, $encryptData, $pu_key);  
    $crypto = $crypto . $encryptData;
  }
	$crypto = base64_encode($crypto);
  return $crypto;
}
#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_account = $row['mer_account'];
$accountexp = explode('###',$pay_account);
$pay_md5key = $accountexp[0];
$pay_account = $accountexp[1];//商户公钥
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#公钥加头尾
$public_key = "-----BEGIN PUBLIC KEY-----\r\n";
foreach (str_split($pay_account,64) as $str){
	$public_key .= $str . "\r\n";
}
$public_key .="-----END PUBLIC KEY-----";
#固定参数设置
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$random = (String)rand(1000,9999);

#第三方参数设置
$data = array(
  "merchNo" => $pay_mid, //商户号
  "netwayType" => '',//支付方式
  "randomNo" => $random,
  "orderNo" => $order_no,//商户流水号
  "amount" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),//订单金额：单位/元
  "goodsName" => 'Buy',//商品名称
  "notifyUrl" => $merchant_url,//通知地址
  "notifyViewUrl" => $return_url
);
#变更参数设置

$form_url = 'http://pay.longfapay.com:88/api/pay';

if(_is_mobile()){
  $scan = 'yl';
  $data['netwayType'] = 'UNION_WAP';
}else{
  $scan = 'yl';
  $data['netwayType'] = 'UNION_WALLET';
}
payType_bankname($scan, $pay_type);
#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}
ksort($data);
$sign = strtoupper(md5(jsonEncode($data) . $pay_md5key));
$data['sign'] = $sign;
//生成 json字符串
$json = jsonEncode($data);
//加密
$dataStr =encodePay($json,$public_key);
$reqParam = 'data=' . urlencode($dataStr) . '&merchNo=' . $pay_mid . '&version=' . 'V3.6.0.0';
#curl获取响应值
$res = curl_post($form_url, $reqParam);
$tran = mb_convert_encoding($res, "UTF-8", "auto");
$row = json_decode($tran, 1);
#跳转
if ($row['stateCode'] != '00') {
  echo '错误代码:' . $row['stateCode'] . "<br>";
  echo '错误讯息:' . $row['msg'] . "<br>";
  exit;
} 
else {
  if (_is_mobile()) {
    header("location:" . $row['qrcodeUrl']);
  } else {
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($row['qrcodeUrl']);
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
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
      if (isset($form_data)) {
        foreach ($form_data as $arr_key => $arr_value) {
          ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php 
    }
  } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

