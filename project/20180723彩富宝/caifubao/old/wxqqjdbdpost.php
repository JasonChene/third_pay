<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
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


#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($data)]);
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
#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
//$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//商戶私钥
$pay_mid = $row['mer_account'];//商戶號
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

#第三方参数设置
$data = array(
  "version" => "4.0",
  "app_id" => $pay_mid, 
  "pay_type" => "",
  "nonce_str" => $order_no,
  "sign" => "",
  "sign_type" => "MD5",
  "body" => "pay",
  "out_trade_no" => $order_no,
  "fee_type" => "CNY",
  "total_fee" => number_format($_REQUEST['MOAmount']*100, 0, '.', ''),
  "return_url" => $return_url,
  "notify_url" => $merchant_url,
  "system_time" => date("YmdHis"),
);
#变更参数设置

$form_url = 'https://pay.i6pay.com/pay/unified/order';//提交地址
if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $scan = 'jd';
  $data['pay_type'] = '10';
}elseif (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包")) {
  $scan = 'qq';
  $data['pay_type'] = '6';
  if(_is_mobile()){
    $data['pay_type'] = '21';
  }
}elseif (strstr($_REQUEST['pay_type'], "百度钱包")) {
  $scan = 'bd';
  $data['pay_type'] = '14';
}else {
  $scan = 'wx';
  $data['pay_type'] = '2';
  if(_is_mobile()){
    $data['pay_type'] = '12';
  }
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
$signtext .= 'app_id='.$data['app_id'];
$signtext .= '&nonce_str='.$data['nonce_str'];
$signtext .= '&out_trade_no='.$data['out_trade_no'];
$signtext .= '&sign_type='.$data['sign_type'];
$signtext .= '&total_fee='.$data['total_fee'];
$signtext .= '&version='.$data['version'];
$signtext .= '&key='.$pay_mkey ;
// $signtext = substr($signtext,0,-1).'&key='.$pay_mkey;
$sign = strtoupper(md5($signtext));
$data['sign'] = $sign;
$data_json = json_encode($data,JSON_UNESCAPED_SLASHES);
#curl获取响应值
$res = curl_post($form_url,$data_json);
$row = json_decode($res,1);
#跳转
if ($row['return_code'] != true) {
    echo  '错误代码:' . "false"."<br>";
    echo  '错误讯息:' . $row['return_msg']."<br>";
    exit;
}else {
    if ($row['result_code'] != true) {
        echo  '错误代码:' . $row['err_code']."<br>";
        echo  '错误讯息:' . $row['err_code_des']."<br>";
        exit;
    }else {
        if(_is_mobile()){
            $jumpurl = $row['code_url'];
        }else{
            $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' .QRcodeUrl($row['code_url']);
        }
    
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
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
      if(isset($form_data)){
        foreach ($form_data as $arr_key => $arr_value) {
      ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php }} ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
