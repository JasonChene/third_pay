<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
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
$top_uid = $_REQUEST['top_uid'];

$pay_type = urldecode($_REQUEST['pay_type']);
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wy_returnUrl'];
$notify_url = $row['pay_domain'] . $row['wy_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
$form_url = 'http://www.xiaocaofu.com/PayOrder/payorder';  //提交地址
$orderno = getOrderNo();  //随机生成商户订单编号
$amount = number_format($_REQUEST['MOAmount'], 0, '.', ''); //订单金额

$parms = array(
  "partner" => $pay_account,//PID
  "user_seller" => $pay_mid,
  "out_order_no" => $orderno,
  "subject" => "Buy",
  "total_fee" => $amount,
  "notify_url" => $notify_url,
  "return_url" => $return_url
);

ksort($parms);
$signtext='';

foreach ($parms as $arr_key => $arr_value) {
  if($arr_value == "" || $arr_value == NULL){
  }else{
    $signtext .= $arr_key . '=' . $arr_value . '&';
  }
}
$signtext2=substr($signtext, 0,-1).$pay_mkey;

$sign = md5($signtext2);
$parms['sign'] = $sign;
  $payType = $pay_type . "_wy";
  $bankname = $pay_type . "->网银在线充值";


$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderno, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php foreach ($parms as $arr_key => $arr_value) {?>      
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>