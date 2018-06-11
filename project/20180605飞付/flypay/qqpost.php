<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

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
$stmt = $mydata1_db->prepare($sql);
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

$array = array(
  '红木家具非洲酸枝木餐桌卷书餐桌椅实木长方形餐桌餐桌组合',
  '红木餐桌圆桌转盘花梨木刺猬紫檀中式实木雕花圆台餐桌椅组合',
  '红木家具印尼黑酸枝木圆桌餐桌阔叶黄檀餐桌椅组合实木古典圆台',
  '红木圆餐桌非洲花梨木圆桌酸枝木餐台实木桌椅组合电动大圆台圆形',
  '红木家具餐桌圆桌印尼黑酸枝圆形雕花圆台阔叶黄檀餐桌椅组合中式',
  '刺猬紫檀红木家具圆桌花梨木圆形餐桌椅组合中式古典家具',
  '非洲酸枝木餐桌红木金玉满堂长方形餐桌中式实木餐厅桌椅组合',
  '阔叶黄檀餐桌中式红木印尼黑酸枝木方形餐桌餐厅桌椅组合家具',
  '王木匠花梨木刺猬紫檀圆桌餐桌椅组合小户型中式红木餐厅家具',
  '王木匠鸡翅木餐桌椅一桌四凳小户型圆形餐桌实木红木家具简约圆桌'
);

#第三方参数设置
$data = array(
  //基本参数
  "merchant_code" => $pay_mid, //商家号
  "service_type" => 'weixin_scan', //业务类型
  "notify_url" => $merchant_url, //服务器异步通知地址
  "interface_version" => 'V3.1', //接口版本
  "client_ip" => getClientIp(), //客户端IP
  "sign_type" => 'RSA-S', //签名方式
  // "sign" => '', //签名

  //业务参数
  "order_no" => $order_no, //商户网站唯一订单号
  "order_time" => date("Y-m-d H:i:s"), //商户订单时间
  "order_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //商户订单总金额
  "product_name" => $array[rand(0, 9)], //商品名称
  // "product_code" => '', //商品编号
  // "product_num" => '', //商品数量
  // "product_desc" => '', //商品描述
  // "extra_return_param" => '', //公用回传参数
  // "extend_param" => '', //公用业务扩展参数
);

#变更参数设置
$scan = 'qq';
$data['service_type'] = 'tenpay_scan';
if (_is_mobile()) {
  $data['service_type'] = 'qq_h5api';
}
$bankname = $pay_type . "->QQ钱包在线充值";
$payType = $pay_type . "_qq";
if (_is_mobile()) {
  $form_url = 'https://api.zdfmf.com/gateway/api/h5apipay';//H5提交地址
} else {
  $form_url = 'https://api.zdfmf.com/gateway/api/scanpay';//扫码提交地址
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
$noarr = array('sign_type', 'sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1);

$merchant_private_key = openssl_get_privatekey($pay_mkey);
if (!$merchant_private_key) {
  echo '打开私钥失败';
  exit;
}
openssl_sign($signtext, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);
$sign = base64_encode($sign_info);
$data['sign'] = $sign;

#curl获取响应值
$res = curl_post($form_url, http_build_query($data));
$xml = (array)simplexml_load_string($res) or die("Error: Cannot create object");
$row = json_decode(json_encode($xml), 1);//XML回传资料

#跳转
if ($row['response']['resp_code'] != 'SUCCESS') {
  echo '错误代码:' . $row['response']['resp_code'] . "\n";
  echo '错误讯息:' . $row['response']['resp_desc'] . "\n";
  exit;
} elseif ($row['response']['result_code'] != '0') {
  echo '错误代码:' . $row['response']['result_code'] . "\n";
  echo '错误讯息:' . $row['response']['result_desc'] . "\n";
  exit;
} else {
  if (!_is_mobile()) {
    if (strstr($row['response']['qrcode'], "&")) {
      $code = str_replace("&", "aabbcc", $row['response']['qrcode']);//有&换成aabbcc
    } else {
      $code = $row['response']['qrcode'];
    }
    $jumpurl = ('../qrcode/qrcode.php?type=' . $scan . '&code=' . $code);
  } else {
    $jumpurl = urldecode($row['response']['payURL']);
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
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
<?php 
} ?>
