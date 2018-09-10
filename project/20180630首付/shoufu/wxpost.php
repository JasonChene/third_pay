<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");


$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function curl_post($url, $data)
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



//获取第三方资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
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


#判别参数
$scan = 'wx';
if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $scan = 'jd';
} elseif (strstr($_REQUEST['pay_type'], "百度钱包")) {
  $scan = 'bd';
}
#固定参数设置
if (_is_mobile()) {
  $form_url = 'http://a.bzzdp.com/api/createWapOrder';//wap提交地址
} else {
  $form_url = 'http://a.bzzdp.com/api/createOrder';//扫码提交地址
}
$order_no = getOrderNo();
#第三方参数设置
$parms = array(
  "merchantNumber" => $pay_mid, //商户号
  "transAmount" => $_REQUEST['MOAmount'],//订单金额：单位/元
  "transNo" => $order_no,//商户流水号
  "payWay" => '',//支付方式
  "tradeName" => 'Buy',//商品名称
  "callBackUrl" => $merchant_url,//通知地址
  "remark" => 'yesOhyes',//备注
  "settlement" => 'T1'//结算方式
);
#变更参数设置
if ($scan == 'wx') {
  $parms['payWay'] = 'wx';
  $payType = $pay_type . "_wx";
  $bankname = $pay_type . "->微信在线充值";
} elseif ($scan == 'jd') {
  $parms['payWay'] = 'jd';
  $bankname = $pay_type . "->京东钱包在线充值";
  $payType = $pay_type . "_jd";
} elseif ($scan = 'bd') {
  $parms['payWay'] = 'baidu';
  $bankname = $pay_type . "->百度钱包在线充值";
  $payType = $pay_type . "_bd";
}

ksort($parms);
$noarr = array('sign');
$signText = '';
$data = '';
foreach ($parms as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signText .= $arr_key . '=' . $arr_val . '&';
  }
  $data .= $arr_key . '=' . $arr_val . '&';
}

$signText = $signText . $pay_mkey;
$sign = md5($signText);
$data .= 'sign=' . $sign;

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

$res = curl_post($form_url, $data);
$tran = iconv("gb2312", "UTF-8", $res);
$row = json_decode($tran, 1);
if ($row['respCode'] == '0000') {
  if (!_is_mobile()) {
    if (strstr($row['qrcodeUrl'], "&")) {
      $code = str_replace("&", "aabbcc", $row['qrcodeUrl']);//有&换成aabbcc
    } else {
      $code = $row['qrcodeUrl'];
    }
    header("location:" . '../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
    exit;
  }
} else {
  echo '错误代码:' . $row['respCode'] . '<br>';
  echo '错误讯息:' . $row['respInfo'] . '<br>';
}

if ($row['respCode'] == '0000' && _is_mobile()) :
?>
<script language="javascript">document.location.href = '<?php echo $row['qrcodeUrl']; ?>';</script>
<?php endif; ?>
