<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}


//获取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);//现数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商户金钥
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$form_url = 'http://gateway.xunbaopay9.com/chargebank.aspx';  //提交地址
$parter = $pay_mid; 	//商户号
$value = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单支付金额,小数点两位
$orderid = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);  //随机生成商户订单编号
$callbackurl = $merchant_url;  //异步回传地址
$hrefbackurl = $return_url; //同步回传地址
//判斷付款種類
$ylscan = false;
$ylkjscan = false;

if (strstr($_REQUEST['pay_type'], "银联钱包")) {
  $ylscan = true;
} elseif (strstr($_REQUEST['pay_type'], "银联快捷")) {
  $ylkjscan = true;
}


// H5选择type
if ($ylscan == true) {
  $type = '7011';//银联錢包扫码
} else if ($ylkjscan == true) {
  $type = '2000';//银联快捷
} else {
  $type = $_REQUEST['bank_code'];//银行编码
}

$parms = array(
  "parter" => $parter,//商户号
  "type" => $type,
  "value" => $value,//金額
  "orderid" => $orderid,
  "callbackurl" => $callbackurl,//异步回传地址
);


$signText = '';
$data = '';
foreach ($parms as $key => $val) {
  if ($val == '') {

  } else {
    $signText .= $key . '=' . $val . '&';
    $data .= $key . '=' . $val . '&';
  }
}

$signText = substr($signText, 0, -1) . $pay_mkey;

$sign = strtolower(md5($signText));

$data = $data . 'sign=' . $sign;





if ($ylscan) {
  $bankname = $pay_type . "->银联钱包在线充值";
  $payT = $pay_type . "_yl";
} else if ($ylkjscan) {
  $bankname = $pay_type . "->银联快捷在线充值";
  $payT = $pay_type . "_ylkj";
} else {
  $bankname = $pay_type . "->网银在线充值";
  $payT = $pay_type . "_wy";
}

$result_insert = insert_online_order($_REQUEST['S_Name'], $orderid, $value, $bankname, $payT, $top_uid);
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
  <body onLoad="document.dinpayForm.submit();">
    <form name="dinpayForm" action="<?php echo $form_url ?>" method="get" id="frm1" target="_self">
        <p>正在为您跳转中，请稍候......</p>
        <?php foreach ($parms as $key => $val) { ?>
          <input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $val; ?>"/>
        <?php 
      } ?>
          <input type="hidden" name="sign" id="sign" value="<?php echo $sign; ?>"/> 
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
