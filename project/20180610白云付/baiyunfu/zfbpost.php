<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
date_default_timezone_set('PRC');
if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}
#function
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
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
  "fxid" => $pay_mid, //商户号
  "fxddh" => $order_no,//商户流水号
  "fxdesc" => 'iPhone',//商品名称
  "fxfee" => number_format($_REQUEST['MOAmount'], 2, '.', ''),//订单金额：单位/元
  "fxattch" => 'Pay',//附加讯息
  "fxnotifyurl" => $merchant_url,//通知地址
  "fxbackurl" => $return_url,//同步
  "fxpay" => 'zfbewm',//支付方式
  "fxip" => getClientIp()//IP
);
#变更参数设置
$form_url = 'http://api.baiyunpay.com/Pay';//提交地址
$scan = 'zfb';
$payType = $pay_type . "_zfb";
$bankname = $pay_type . "->支付宝在线充值";
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

$yesarr = array('fxid','fxddh','fxfee','fxnotifyurl');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( in_array($arr_key, $yesarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
    $signtext .= $arr_val;
	}
}
$signtext .= $pay_mkey;
$sign = md5($signtext);
$data['fxsign'] = $sign;
#curl获取响应值
$res = curl_post($form_url,http_build_query($data));
$row = json_decode($res,1);
#跳转
if ($row['status'] != '1') {
  echo  '错误讯息:' . $row['error']."\n<br>";
  exit;
}else {
  $jumpurl = $row['payurl'];
}
#跳轉方法
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>

    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>


