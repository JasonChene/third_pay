<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");


$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function curl_post($url,$data){ #POST访问
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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



//获取第三方资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$mer_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

#判断参数
$scan = 'wy';
if (strstr($_REQUEST['pay_type'], "银联钱包")){
  $scan = 'yl';
}elseif (strstr($_REQUEST['pay_type'], "银联钱包")) {
  $scan = 'ylkj';
}
#固定参数设置
$order_no = getOrderNo();
$form_url = 'https://ebank.nicefpay.com/payment/v1/order/'.$pay_mid.'-'.$order_no;//扫码提交地址
#第三方参数设置
$parms = array(
  "body" => 'BuyDDog',
  "charset" => 'UTF-8',
  "defaultbank" => '',//微信 京东 参数设定
  "isApp" => '',//app=二维码 web=收银台产生二维码 h5=手机
  "merchantId" => $pay_mid, //商户号
  "notifyUrl" => $merchant_url,//异步
  "orderNo" => $order_no,//商户流水号
  "paymentType" => '1',//支付类型，固定值为1
  "paymethod" => 'directPay',//支付方式，directPay：直连模式；bankPay：收银台模式
  "returnUrl" => $return_url,//通知地址
  "service" => 'online_pay',//固定值online_pay，表示网上支付
  "title" => 'BuyDDog',//商品的名称
  "totalFee" => number_format($_REQUEST['MOAmount'], 2, '.', ''),//订单金额
  "signType" =>'SHA'
);
#变更参数设置
if ($scan == 'wy') {
  $parms['isApp'] = 'web';
  $parms['paymethod'] = 'bankPay';
  $payType = $pay_type."_wy";
  $bankname = $pay_type . "->网银在线充值";
}elseif ($scan == 'yl') {
  $parms['isApp'] = 'app';
  $parms['defaultbank'] = 'UNIONQRPAY';
  $payType = $pay_type."_yl";
  $bankname = $pay_type."->银联钱包在线充值";
}elseif ($scan == 'ylkj') {
  $parms['isApp'] = 'web';
  $parms['paymethod'] = 'bankPay';
  $parms['defaultbank'] = 'QUICKPAY';
  $payType = $pay_type."_wy";
  $bankname = $pay_type . "->银联快捷在线充值";
}


ksort($parms);
$noarr =array('signType');
$signText = '';
$data = '';
foreach ($parms as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signText .= $arr_key.'='.$arr_val.'&';
	}
  $data .= $arr_key.'='.$arr_val.'&';
}

$signText = substr($signText,0,-1).$pay_mkey;
$sign = strtoupper(sha1($signText));
$data .= 'sign='.$sign;
$parms['sign'] = $sign;

$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');
//確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $order_no, $mymoney, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

if ($scan == 'yl') {
  $res = curl_post($form_url,$data);
  $row = json_decode($res,1);
  if ($row['respCode'] == 'S0001') {
    header("location:" .'../qrcode/qrcode.php?type='.$scan.'&code=' .$row['codeUrl']);
    exit;
  }else {
    echo  '错误代码:' . $row['respCode'].'<br>';
    echo  '错误讯息:' . $row['respMessage'].'<br>';
  }
}else {
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
<?php } ?>
