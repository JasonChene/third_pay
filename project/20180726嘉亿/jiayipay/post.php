<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function payType_bankname($scan,$pay_type){
  global $payType, $bankname;
  if(strstr($scan,"wy")){
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
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
  }elseif(strstr($scan,"yl")){
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  }elseif(strstr($scan,"ylkj")){
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  }elseif(strstr($scan,"bd")){
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  }
}


#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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
function create_sign($data,$key){
  ksort($data);
  $sign = strtoupper(md5(json_encode_ex($data) . $key));
  return $sign;
}
function json_encode_ex($value){
  if (version_compare(PHP_VERSION,'5.4.0','<')){
   $str = json_encode($value);
   $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i","replace_unicode_escape_sequence",$str);
   $str = stripslashes($str);
   return $str;
 }else{
   return json_encode($value,320);
 }
}
function encode_pay($data,$pay_public_key){#加密//		
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
function json_to_array($json,$key){
  $array=json_decode($json,true);
  if ($array['stateCode'] == '00'){
    $sign_string = $array['sign'];
    ksort($array);
    $sign_array = array();
    foreach ($array as $k => $v) {
      if ($k !== 'sign'){
        $sign_array[$k] = $v;
      }
    }
    // 生成签名 并将字母转为大写
    $md5 =  strtoupper(md5(json_encode_ex($sign_array) . $key));
     if ($md5 == $sign_string){
       return $sign_array;
     }else{
       $result = array();
       $result['stateCode'] = '99';
       $result['msg'] = '返回签名验证失败';
       return $result;
     }
  }else{
    $result = array();
     $result['stateCode'] = $array['stateCode'];
     $result['msg'] = $array['msg'];
    return $result;
  }
}
#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$idArray = explode("###", $pay_mkey);
$md5key = $idArray[0];//合作方md5密钥
$private_key = $idArray[1];//合作方私钥
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#固定参数设置
$public_pem = chunk_split($pay_account,64,"\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n".$public_pem."-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($private_key,64,"\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN PRIVATE KEY-----\r\n".$private_pem."-----END PRIVATE KEY-----\r\n";
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
#第三方参数设置
$data = array(
  "orderNum" => $order_no,
  "version" => "V3.1.0.0", 
  "charset" => "UTF-8",
  "random" => (string)rand(1000,9999),//随机数,
  "merNo" => $pay_mid,
  "netway" => $_REQUEST['bank_code'],
  "amount" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),
  "goodsName" => "pay",
  "callBackUrl" => $merchant_url,
  "callBackViewUrl" => $return_url,
);
#变更参数设置
$form_url = 'http://api.jiayipay.cn/api/pay.action';//提交地址
$scan = 'wy';

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
//生成签名
$data['sign'] = create_sign($data,$md5key);
//生成 json字符串
$json = json_encode_ex($data);
//加密
$dataStr =encode_pay($json,$public_pem);

//请求字符串
$param = 'data=' . urlencode($dataStr) . '&merchNo=' . $data['merNo'] . '&version='.$data['version'];
#curl获取响应值
$res = curl_post($form_url,$param);
//效验 sign
$rows = json_to_array($res,$md5key);
if($rows['stateCode']==0){
	echo "下单成功,返回的结果如下";
	header('Location: '.$rows['qrcodeUrl'].''); 
}else{
	echo "下单失败 ,".$rows['stateCode'] .$rows['msg'];
}
