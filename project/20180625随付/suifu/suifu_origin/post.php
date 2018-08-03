<?php
include_once("../../../database/mysql.config.php");
// include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
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
#获取第三方资料

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#获取第三方资料(非必要不更动)
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//私鑰
$pay_account = $row['mer_account'];//公鑰
$return_url = $row['pay_domain'] . $row['wx_postUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
$pay_type = $_REQUEST['pay_type'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#固定参数设置
$public_pem = chunk_split($pay_account, 64, "\r\n");//转换为pem格式的公钥
$public_pem = "-----BEGIN PUBLIC KEY-----\r\n" . $public_pem . "-----END PUBLIC KEY-----\r\n";
$private_pem = chunk_split($pay_mkey, 64, "\r\n");//转换为pem格式的私钥
$private_pem = "-----BEGIN RSA PRIVATE KEY-----\r\n" . $private_pem . "-----END RSA PRIVATE KEY-----\r\n";
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  "merchant_code" => $pay_mid, //商戶id
  "service_type" => "direct_pay",//业务类型
  "notify_url" => $merchant_url,//服务器异步通知地址
  "interface_version" => 'V3.0',//接口版本
  "input_charset" => "UTF-8",//参数编码字符集
  "sign_type" => "RSA-S",//签名方式,,不参与签名
  "sign" => "",//簽名,不参与签名
  "pay_type" => "",//支付类型
  "order_no" => $order_no,//商家订单号
  "order_time" => date('Y-m-d H:i:s'),//商户订单时间
  "order_amount" => $mymoney,//商户订单总金额
  // "bank_code" => "ABC",//直接跳转到收银台选择银行页面
  "product_name" => "pay"//商品名称
);

#变更参数设置
$form_url = "https://pay.suifupay.com/gateway?input_charset=UTF-8";//网银支付

if (strstr($pay_type, "银联钱包")) {
  $scan = 'yl';
  $data['pay_type'] = 'b2cwap';
  $data['bank_code'] = 'WAP_UNION';
  $bankname = $pay_type . "->银联钱包在线充值";
  $payType = $pay_type . "_yl";
} else {
  $scan = 'wy';
  $data['pay_type'] = 'b2c';
  $bankname = $pay_type . "->网银在线充值";
  $payType = $pay_type . "_wy";
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
$noarr = array('sign_type', 'sign');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1);//验签字串

#RSA-S签名
$privatekey = openssl_get_privatekey($private_pem);
if ($privatekey == false) {
  echo "打开私钥出错";
  exit();
}
$pub = openssl_sign($signtext, $sign_info, $privatekey, OPENSSL_ALGO_MD5);
if ($pub) {
  $data['sign'] = base64_encode($sign_info);
} else {
  echo "加密失敗";
  exit();
}

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url ?>" target="_self">
    <p>正在为您跳转中，请稍候......</p>
    <?php foreach ($data as $arr_key => $arr_value) { ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
    <?php 
  } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
   </body>
 </html>