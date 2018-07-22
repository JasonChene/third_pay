<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");//原新数据库的连接方式
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

#function
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
  curl_close($ch);
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

function QRcodeUrl($code)
{ #替换QRcodeUrl中&符号
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
// $stmt = $mydata1_db->prepare($sql);//原新数据库的连接方式
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
  "p0_Cmd" => 'Buy', //业务类型
  "p1_MerId" => $pay_mid, //商户编号
  "p2_Order" => $order_no, //商户订单号
  "p3_Amt" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //支付金额
  "p4_Cur" => 'CNY', //交易币种
  "p5_Pid" => 'Pid', //商品名称
  "p6_Pcat" => 'Pcat', //商品种类
  "p7_Pdesc" => 'Pcat', //商品描述
  "p8_Url" => $merchant_url, //商户接收支付成功数据的地址
  "p9_SAF" => '0', //送货地址
  "pa_MP" => 'MP', //商户扩展信息
  "pd_FrpId" => '', //支付通道编码
  "pr_NeedResponse" => '1', //应答机制
  "sign" => '', //签名数据
);

#变更参数设置
$form_url = 'https://master-egg.cn/GateWay/ReceiveBank.aspx';//请求地址
if (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $data['pd_FrpId'] = 'jdpay';
} elseif (strstr($pay_type, "QQ钱包") || strstr($pay_type, "qq钱包")) {
  $scan = 'qq';
  $data['pd_FrpId'] = 'qqpay';
  if (_is_mobile()) {
    $data['pd_FrpId'] = 'qqpayh5';
  };
} elseif (strstr($pay_type, "支付宝")) {
  $scan = 'zfb';
  $data['pd_FrpId'] = 'alipay';
  if (_is_mobile()) {
    $data['pd_FrpId'] = 'alipayh5';
  }
} else {
  $scan = 'wx';
  $data['pd_FrpId'] = 'wxcode';
  if (_is_mobile()) {
    $data['pd_FrpId'] = 'wechath5';
  }
}

// if (strstr($pay_type, "银联钱包")) {
//   $scan = 'yl';
//   $data['pd_FrpId'] = 'unionpay';
// } elseif (strstr($pay_type, "银联快捷")) {
//   $scan = 'ylkj';
//   $data['pd_FrpId'] = 'quickpay';
// } else {
//   $scan = 'wy';
//   $data['pd_FrpId'] = $_REQUEST['bank_code'];
// }
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
$noarr = array('sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr)) {
    $signtext .= $arr_val;
  }
}
$res = openssl_get_privatekey($pay_mkey);
openssl_sign($signtext, $sign, $res);
openssl_free_key($res);
$sign = base64_encode($sign);
$data['sign'] = $sign;
$data_str = http_build_query($data);

#curl获取响应值
$res = curl_post($form_url, $data_str);
$tran = mb_convert_encoding("$res", "UTF-8");
$row = json_decode($tran, 1);

//打印
echo '<pre>';
echo ('<br> data = <br>');
var_dump($data);
echo ('<br> signtext = <br>');
echo ($signtext);
echo ('<br><br> row = <br>');
var_dump($row);
echo '</pre>';

exit;

#跳转
if ($row['respCode'] != '0000') {
  echo '错误代码:' . $row['respCode'] . "\n";
  echo '错误讯息:' . $row['respInfo'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['qrcodeUrl'];
  if (_is_mobile()) {
    $jumpurl = $qrcodeUrl;
  } else {
    $jumpurl = '../qrcode/qrcode.php?type=' . $scan . '&code=' . QRcodeUrl($qrcodeUrl);
  }
}

#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

