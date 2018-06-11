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
    '红木餐桌','圆桌','转盘','花梨木','刺猬','紫檀中式实木','雕花圆台','餐桌椅组合',
    '王木匠','鸡翅','木餐桌椅一桌','四凳小户型圆形餐桌','实木红木家具简约圆桌'
);
#第三方参数设置
$data = array(
  //基本参数
  "merchant_code" => $pay_mid, //商家号
  "service_type" => 'direct_pay', //服务类型
  "notify_url" => $merchant_url, //服务器异步通知地址
  "interface_version" => 'V3.0', //接口版本
  "input_charset" => 'UTF-8', //参数编码字符集
  "sign_type" => 'RSA-S', //签名方式
  //"sign" => '', //签名
  "return_url" => $return_url, //页面跳转同步通知地址
  "pay_type" => 'b2c', //参数编码字符集
  "client_ip" => getClientIp(), //客户端IP
  "client_ip_check" => '1', //客户端IP是否校验标识
  "order_no" => $order_no, //商家订单号
  "order_time" => date("Y-m-d H:i:s"), //商家订单时间
  "order_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //商家订单金额
  "bank_code" => '', //银行代码
  "redo_flag" => '1', //是否允许重复订单
  // "product_name" => '诺基亚_7_Plus_Nokia_7_Plus', //商品名称
  "product_name" => $array[rand(0,12)], //商品名称
  // "product_code" => '', //商品编号
  // "product_num" => '', //商品数量
  // "product_desc" => '', //商品描述
  // "extra_return_param" => '', //回传参数
  // "extend_param" => '', //业务扩展参数
  // "show_url" => '', //商品展示URL
  // "orders_info" => '' //储存子订单的相关信息
);

#变更参数设置
$form_url = 'https://pay.zdfmf.com/gateway?input_charset=UTF-8';//网银提交地址
if (strstr($pay_type, "银联钱包")) {
  $scan = 'yl';
  $data['service_type'] = 'direct_pay';
  $bankname = $pay_type . "->银联钱包在线充值";
  $payType = $pay_type . "_yl";
  $data["pay_type"] = 'b2cwap'; //支付类型
} else {
  $scan = 'wy';
  $data['service_type'] = 'direct_pay';
  $bankname = $pay_type . "->网银在线充值";
  $payType = $pay_type . "_wy";
  $data["pay_type"] = 'b2c'; //支付类型
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

#跳轉方法
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
