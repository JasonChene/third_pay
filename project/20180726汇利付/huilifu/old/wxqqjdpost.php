<?php
#第三方名稱 : 汇利付
#支付方式 : wx;
include_once("./addsign.php");
include_once("../moneyfunc.php");
// include_once("../../../database/mysql.php");
include_once("../../../database/mysql.config.php");


$S_Name = $_REQUEST['S_Name'];
$top_uid = $_REQUEST['top_uid'];
$pay_type =$_REQUEST['pay_type'];
#跳转qrcode.php网址调试
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}


#修正url
function fix_postdata_url($url, $data){
    $post_url='';
    if(substr($url,-1) == '?' || substr($url,-1) == '/'){
      $post_url=substr($url,0,-1)."?".$data;
 }else{
       $post_url=$url."?".$data;
 }
 return $post_url ;
}


#curl请求设定
function curl_post($url, $data){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    echo(curl_errno($ch));
    exit;
  }
  curl_close($ch);
  return $tmpInfo;
}


#获取第三方资料(非必要不更动)
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
// $stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//同步
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//异步
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


#固定参数设置
$form_url = 'http://39.104.106.227/lh_pay/pay';
$bank_code = $_REQUEST['bank_code'];
$order_no = getOrderNo();
$notify_url = $merchant_url;
$client_ip = getClientIp();
$pr_key = $pay_mkey;//私钥
$pu_key = $pay_account;//公钥
$order_time = date("YmdHis");


$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
$MOAmount = number_format($_REQUEST['MOAmount']*100, 0, '.', '');
#第三方传值参数设置
$data = array(
"sign" => array(
"str_arr" => array(
"ip" => $client_ip,
"mch_id" => $pay_mid,
"notify_url" => $notify_url,
"out_trade_no" => $order_no,
"pay_type" => "101",
"total_fee" => $MOAmount,
),
"mid_conn" => "=",
"last_conn" => "&",
"encrypt" => array(
"0" => "MD5",
),
"havekey" => "1",
"key_str" => "&key=",
"key" => $pr_key,
),
"mch_id" => $pay_mid,
"out_trade_no" => $order_no,
"total_fee" => $MOAmount,
"notify_url" => $notify_url,
"pay_type" => '101',
"ip" => $client_ip,
);
#变更参数设定
$payType = $pay_type."_wx";
$bankname = $pay_type."->微信在线充值";
if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $data['sign']['str_arr']['pay_type'] = '201';
  $data['pay_type'] = '201';
  $bankname = $pay_type."->京东钱包在线充值";
  $payType = $pay_type."_jd";
}elseif (strstr($_REQUEST['pay_type'], "QQ钱包") || strstr($_REQUEST['pay_type'], "qq钱包")) {
  $data['sign']['str_arr']['pay_type'] = '601';
  $data['pay_type'] = '601';
  $bankname = $pay_type."->QQ钱包在线充值";
  $payType = $pay_type."_qq";
}
#新增至资料库，確認訂單有無重複， function在 moneyfunc.php裡(非必要不更动)
$result_insert = insert_online_order($S_Name , $order_no , $mymoney,$bankname,$payType,$top_uid);
if ($result_insert == -1){
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2){
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}


#签名排列，可自行组字串或使用http_build_query($array)
foreach ($data as $arr_key => $arr_value) {
  if (is_array($arr_value)) {
    $data[$arr_key] = sign_text($arr_value);
  }
}
$data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

#curl获取响应值
$res = curl_post($form_url,$data_str);
$row = json_decode($res,1);
#跳转qrcode
$url = $row['pay_url'];
if ($row['status'] == '200') {
  $jumpurl = $url;
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
<?php
}else {
  echo "错误码：".$row['status']."错误讯息：".urldecode($row['message']);
}
?>
