<?php
header("Content-type:text/html; charset=utf-8");
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
  "version" => 'v1', //接口版本
  "merchant_no" => $pay_mid, //商户号
  "order_no" => $order_no, //商户订单号
  "goods_name" => base64_encode("goodsname"), //商品名称
  "order_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //订单金额
  "backend_url" => $merchant_url, //接受AI后台异步订单状态通知的地址
  "frontend_url" => $return_url, //商户的支付结果显示页面地址
  "reserve" => 'reserve', //商户保留信息
  "pay_mode" => '', //支付模式 09:扫码支付 12：H5支付模式
  "bank_code" => '', //银行编号
  "card_type" => '0', //允许支付的卡类型
  "sign" => '', //签名数据
);

#变更参数设置
$form_url = 'https://pay.all-inpay.com/gateway/pay.jsp';//支付提交地址
$scan = 'qq';
$data['bank_code'] = 'QQSCAN';
$data['pay_mode'] = '09';
if (_is_mobile()) {
  $data['bank_code'] = 'QQWAP';
  $data['pay_mode'] = '12';
}
$bankname = $pay_type . "->QQ钱包在线充值";
$payType = $pay_type . "_qq";

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
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1) . '&key=' . $pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;
$data_str = http_build_query($data);

#curl获取响应值
$res = curl_post($form_url, $data_str);
$tran = mb_convert_encoding("$res", "UTF-8");
// $tran = mb_convert_encoding("$res", "UTF-8", "auto");
$row = json_decode($tran, 1);

#跳转
if ($row['result_code'] != '00') {
  echo '错误代码:' . $row['result_code'] . "\n";
  echo '错误讯息:' . $row['result_msg'] . "\n";
  exit;
} else {
  $qrcodeUrl = $row['code_url'];
  if (!_is_mobile()) {
    if (strstr($qrcodeUrl, "&")) {
      $code = str_replace("&", "aabbcc", $qrcodeUrl);//有&换成aabbcc
    } else {
      $code = $qrcodeUrl;
    }
    $jumpurl = ('../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
  } else {
    $jumpurl = $qrcodeUrl;
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

