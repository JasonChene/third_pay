<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
include_once("./function.php");
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
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
  "version" => "1.0.1",
  "subject" => "iPhone",//商品标题
  "amount" => number_format($_REQUEST['MOAmount'], 0, '.', ''),//订单金额：单位/元
  "notifyUrl" => urlencode($merchant_url),//通知地址
  "orgOrderNo" => $order_no,
  "source" => 'WXZF',//通道
  "extra_para" => $order_no,//原样返回
  "tranTp" => '0'//固定0
);
#变更参数设置
$form_url = 'http://api.easypay188.com/externalSendPay/rechargepay.do';//提交地址
$scan = 'wx';
$payType = $pay_type."_wx";
$bankname = $pay_type . "->微信在线充值";
if (_is_mobile()) {
  $data['source'] = 'WXH5';
}
if (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  if (_is_mobile()) {
    $data['source'] = 'JDH5';
  }else{
    $data['source'] = 'JDQB';
  }
  $bankname = $pay_type."->京东钱包在线充值";
  $payType = $pay_type."_jd";
}elseif (strstr($pay_type, "百度钱包")) {
  $scan = 'bd';
  $data['source'] = 'BDQB';
  $bankname = $pay_type."->百度钱包在线充值";
  $payType = $pay_type."_bd";
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
ksort($data);
$postdata = sign($data,$pay_account,$pay_mkey,$pay_mid);
#curl获取响应值
$res = curl_post($form_url,$postdata);
$row = json_decode($res,1);

#跳转qrcode
if ($row['responseCode'] == '200'){
  if(_is_mobile()){
    $jumpurl = $row['responseObj']['qrCode'];
  }else{
    $qrurl = QRcodeUrl($row['responseObj']['qrCode']);
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' . $qrurl;
  }

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
<?php }else{
  echo '错误代码(responseCode)：'.$row['responseCode'].'<br>';
  echo '错误讯息(responseMessage)：'.$row['responseMessage'].'<br>';
  if($row['responseMessage']=="SUCCESS"){
    echo '错误代码(respCode)：'.$row['responseObj']['respCode'].'<br>';
    echo '错误讯息(respMsg)：'.$row['responseObj']['respMsg'].'<br>';
  }
}
?>
