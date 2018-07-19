<?php
header("Content-type:text/html; charset=UTF-8");
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}



$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$form_url= "http://pay.zzppaa.com/apisubmit";
$version = "1.0";//版本
$customerid = $pay_mid;  //商戶號
$sdorderno = getOrderNo();  //随机生成商户订单编号
$total_fee = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$payType = $pay_type."_qq";
$bankname = $pay_type . "->QQ钱包在线充值";
$paytype = "qqrcode";
if(_is_mobile()){
  $paytype = "qqwap";
}
$notifyurl = $merchant_url;  //异步
$returnurl = $return_url;

$parms = array(
  "version" => $version,//商户号
  "customerid" => $customerid,
  "total_fee" => $total_fee,
  "sdorderno" => $sdorderno,
  "paytype" => $paytype,
  "notifyurl" => $notifyurl,
  "returnurl" => $returnurl,
  "remark" => 'test',
  "get_code" => "1"
);

$signtext='';

foreach ($parms as $arr_key => $arr_value) {
  if($arr_value == "" || $arr_key == "paytype" ||$arr_key == "get_code"||$arr_key == "remark"){
  }else{
    $signtext .= $arr_key . '=' . $arr_value . '&';
  }
}
$signtext2=$signtext.$pay_mkey;

$sign = md5($signtext2);
$parms['sign'] = $sign;

$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$result_insert = insert_online_order($_REQUEST['S_Name'], $sdorderno, $mymoney, $bankname, $payType, $top_uid);
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
