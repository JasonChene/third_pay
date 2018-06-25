<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

function wx_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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




$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

$jdscan = false;
if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $jdscan = true;
}

//獲取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//商戶金鑰
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

$form_url = 'http://gateway.shangyizhifu.com/chargebank.aspx ';  //提交地址
$parter = $pay_mid; 	//商戶號
$value = number_format($_REQUEST['MOAmount'], 2, '.', ''); //訂單支付金額,小數點兩位
$orderid = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);  //隨機生成商户訂單編號
$callbackurl = $merchant_url;  //異步回傳地址
$hrefbackurl = $return_url;



// wap選擇type
if (_is_mobile()) {
  $type = '933';//微信 H5
} else {
  if ($jdscan) {
    $type = '911';//京東錢包掃碼
  } else {
    $type = '8011';//微信掃碼
  }
}



$parms = array(
  "parter" => $parter,
  "type" => $type,
  "value" => $value,
  "orderid" => $orderid,
  "callbackurl" => $callbackurl,
);

// ksort($parms);
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




// 確認訂單有無重複， function在 moneyfunc.php 裡
if ($jdscan) {
  $bankname = $pay_type . "->京东钱包在线充值";
  $payT = $pay_type . "_jd";
} else {
  $bankname = $pay_type . "->微信在线充值";
  $payT = $pay_type . "_wx";
}
//確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $orderid, $value, $bankname, $payT, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

if (_is_mobile()) {
  $return = wx_post($form_url, $data);
  $row = json_decode($return, true);
  header("location:" . $return);
}

?>




<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form action="<?php echo $form_url ?>" method="post" id="frm1" target="_self">
        <p>正在为您跳转中，请稍候......</p>
        <?php foreach ($parms as $key => $val) { ?>
          <input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $val; ?>"/>
        <?php 
      } ?>
        <input type="hidden" name="hrefbackurl" id="hrefbackurl" value="<?php echo $hrefbackurl; ?>"/>
          <input type="hidden" name="sign" id="sign" value="<?php echo $sign; ?>"/> 
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
