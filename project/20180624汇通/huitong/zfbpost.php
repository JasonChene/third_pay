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

#第三方参数设置
$data = array(
  "notify_url" => $merchant_url, //服务器异步通知地址
  "return_url" => $return_url, //页面同步跳转通知地址
  "pay_type" => '', //支付方式 1-网银支付, 2-微信支付, 3-支付宝支付, 5-QQ钱包
  "bank_code" => '', //银行编码
  "merchant_code" => $pay_mid, //商户号
  "order_no" => $order_no, //商户订单号
  "order_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //商户订单总金额
  "order_time" => date("Y-m-d H:i:s"), //商户订单时间
  "req_referer" => $row['pay_domain'], //来路域名 //$_SERVER['HTTP_HOST']
  "customer_ip" => getClientIp(), //消费者IP
  "return_params" => '', //回传参数
  "sign" => '', //签名
);

#变更参数设置
$form_url = 'https://api.huitongvip.com/pay.html';//form接口请求地址
if (_is_mobile()) {
  $form_url = 'https://api.huitongvip.com/order.html';//curl接口请求地址
}

$scan = 'zfb';
$data['pay_type'] = '9';
if (_is_mobile()) {
  $data['pay_type'] = '3';
}
$bankname = $pay_type . "->支付宝在线充值";
$payType = $pay_type . "_zfb";

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
$noarr = array('sign');//不加入签名的array key值
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (isset($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}
$signtext = substr($signtext, 0, -1) . '&key=' . $pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;
$data_str = http_build_query($data);

#curl获取响应值
if (_is_mobile()) {
  $res = curl_post($form_url, $data_str);
  $tran = mb_convert_encoding("$res", "UTF-8");
  $row = json_decode($tran, 1);

#跳转
  if ($row['flag'] != '00') {
    echo '错误代码:' . $row['flag'] . "\n";
    echo '错误讯息:' . $row['msg'] . "\n";
    exit;
  } else {
    $qrcodeUrl = $row['qrCodeUrl'];
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
} else {
  $jumpurl = $form_url;
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
     <?php if (!_is_mobile()) { ?>
       <?php foreach ($data as $arr_key => $arr_value) { ?>
         <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
       <?php 
    } ?>
     <?php 
  } ?>
   </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>

