<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

function payType_bankname($scan, $pay_type)
{
  global $payType, $bankname;
  if ($scan == "wy") {
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  } elseif ($scan == "yl" || $scan == "ylfs") {
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  } elseif ($scan == "qq" || $scan == "qqfs") {
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  } elseif ($scan == "wx" || $scan == "wxfs") {
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  } elseif ($scan == "zfb" || $scan == "zfbfs") {
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  } elseif ($scan == "jd" || $scan == "jdfs") {
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  } elseif ($scan == "ylkj") {
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  } elseif ($scan == "bd" || $scan == "bdfs") {
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  } else {
    echo ('payType_bankname出错啦！');
    exit;
  }
}


#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  // curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
  // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Content-Length: ' . strlen($data)));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ($ch, CURLOPT_REFERER, $url);
  $tmpInfo = curl_exec($ch);
  // $tmpInfo = curl_getinfo($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
}
function QRcodeUrl($code)
{
  if (strstr($code, "&")) {
    $code2 = str_replace("&", "aabbcc", $code);//有&换成aabbcc
  } else {
    $code2 = $code;
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
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶私钥
$idArray = explode("###", $pay_mkey);
$md5key = $idArray[0];//md5密钥
$des_key = $idArray[1];//des密钥
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
  "cmd" => '',
  "version" => '2.0',
  "hmac" => '',
  "appid" => $pay_mid, //商户号
  "ordertime" => date("YmdHis"),
  "userid" => $pay_account,
  "apporderid" => $order_no,//商户流水号
  "orderbody" => 'pay',
  "amount" => $mymoney,//订单金额：单位/元
  "notifyurl" => $merchant_url//通知地址
);
#变更参数设置

$form_url = 'http://47.90.116.46:18000/GW/gw.inter';
$scan = 'qq';
$data['cmd'] = 'PAYQQ';
if (_is_mobile()) {
  $data['cmd'] = 'PAYH5';
  $data['biztype'] = 'qq';
  $data['pageyurl'] = $return_url;
  $form_url = 'http://zs.qilijiakeji.com:18000/GW/payqqh5.do';//提交地址
}

payType_bankname($scan, $pay_type);
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

$data = array_filter($data);
ksort($data);
$signature = sprintf('%s%s', urldecode(http_build_query($data)), $md5key);
$data['hmac'] = md5($signature);

if (!_is_mobile()) {
  #curl获取响应值
  $res = curl_post($form_url, http_build_query($data));
  parse_str($res, $resarr);
  #跳转
  if(!empty($resarr) && $resarr['codeurl'] != null && $resarr['codeurl'] != 'null'){
    $decrypted = openssl_decrypt(base64_encode(hex2bin($resarr['codeurl'])), 'des-ecb', $des_key);
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($decrypted);
  }else {
    echo '错误'. "<br>";
    echo '错误代码:' . $resarr['errcode'] . "<br>";
    echo '错误讯息:' . $resarr['errdesc'] . "<br>";
    exit;
  } 
}else {
  $jumpurl = $form_url;
  $form_data = $data;
}
#跳轉方法

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
      if (isset($form_data)) {
        foreach ($form_data as $arr_key => $arr_value) {
          ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php 
    }
  } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

