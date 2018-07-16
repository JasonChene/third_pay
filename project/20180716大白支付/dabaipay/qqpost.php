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
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
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
function createNoncestr($length) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  $res = '';
  for ($i = 0; $i < $length; $i++) {

      $random = mt_rand(0, strlen($chars)-1);
      $res .= $chars{$random};
  }
  return $res;
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

#第三方参数设置
$data = array(
  "pay_version" => "2.0",
  "pay_memberid" => $pay_mid, //商户号
  "pay_orderid" => $order_no,//商户流水号
  "pay_amount" => (float)number_format($_REQUEST['MOAmount'], 2, '.', ''),//订单金额：单位/元
  "pay_bankcode" => 'qqpay',
  'pay_scene' => 'sm',
  "pay_notifyurl" => $merchant_url,//通知地址
  "pay_callbackurl" => $return_url,//同步
  'pay_rand' => createNoncestr(8)
);
#变更参数设置
$form_url = 'https://scr.open.dabaizf.net/pay/dircleserapi/api.html';//提交地址
$scan = 'qq';
$bankname = $pay_type."->QQ钱包在线充值";
$payType = $pay_type."_qq";
if (_is_mobile()){
  $data['pay_scene'] = 'h5';
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
$kevacon = '=';
$mark = '&';
$signtext = '';
$signtext .= 'pay_version'.$kevacon.$data['pay_version'];
$signtext .= $mark.'pay_amount'.$kevacon.$data['pay_amount'];
$signtext .= $mark.'pay_bankcode'.$kevacon.$data['pay_bankcode'];
$signtext .= $mark.'pay_scene'.$kevacon.$data['pay_scene'];
$signtext .= $mark.'pay_memberid'.$kevacon.$data['pay_memberid'];
$signtext .= $mark.'pay_orderid'.$kevacon.$data['pay_orderid'];
$signtext .= $mark.'pay_notifyurl'.$kevacon.$data['pay_notifyurl'];
$signtext .= $mark.'pay_callbackurl'.$kevacon.$data['pay_callbackurl'];
$signtext .= $mark.'pay_rand'.$kevacon.$data['pay_rand'];
$signtext .= $mark.'pay_key'.$kevacon.$pay_mkey;
$sign = md5($signtext);
$data['pay_sign'] = $sign; 
$postdata['body'] = base64_encode(json_encode($data));
#curl获取响应值
$res = curl_post($form_url,$postdata);
$row = json_decode($res,1);
echo $res.'<br>';
var_dump($row);
exit;
#跳转
if ($row['respCode'] != '0000') {
  echo  '错误代码:' . $row['respCode']."\n";
  echo  '错误讯息:' . $row['respInfo']."\n";
  exit;
}else {

  if(_is_mobile()){
    $jumpurl = $array['payUrl'];
  }else{
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' .QRcodeUrl($array['payUrl']);
  }
}
exit;
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
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>