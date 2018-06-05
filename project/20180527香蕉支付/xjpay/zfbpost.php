<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

#function
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}
function curl_post($url,$data){ #POST访问
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
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
$return_url = $row['pay_domain'] . $row['zfb_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['zfb_synUrl'];//notify回传地址
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
#固定参数设置
$form_url = 'http://pay.8808068.com/pay';//提交地址
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  "out_trade_no" => $order_no,//商户订单号
  "service" => '', //接口类型
  "partner" => $pay_mid,//商户id
  "total_fee" => number_format($_REQUEST['MOAmount'], 2, '.', ''),//签名方式
  "notify_url" => $merchant_url,//订单异步通知地址
  "nonce_str" => rand( 100000, 999999),//随机字符串 随机字符串，不长于32位
  "mch_create_ip" => getClientIp(),
  "sign" => '',//签名
);
#变更参数设置

$scan = 'zfb';
$payType = $pay_type . "_zfb";
$bankname = $pay_type . "->支付宝在线充值";
$data['service'] = 'alipay.ma';//QQ钱包扫码	qqpay.ma
if(_is_mobile()){
    $data['service'] = 'alipay.wap';//QQ钱包手机	qqpay.wap
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
$noarr = array('sign');//不加入签名的array key值
ksort($data);
$signtext="";
foreach ($data as $arr_key => $arr_val) {
	if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0')) {
		$signtext .= $arr_key . '=' . $arr_val . '&';
	}
}
$data['sign'] = strtoupper(md5(substr($signtext,0,-1).$pay_mkey));

#curl获取响应值

$res = file_get_contents($form_url.'?'.http_build_query($data));
$res = iconv("UTF-8", "GB2312//IGNORE", $res);
$row = json_decode($res,1);
#跳转
if ($row['code'] != 0) {
  echo  '错误代码:' . $row['code']."\n";
  echo  '错误讯息:' . $row['msg']."\n";
  exit;
}else {
    if (strstr($data['service'],".wap")) {
        $jumpurl = $row['pay_url'];
    }else {
        $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code='.QRcodeUrl($row['code_url']);
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
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
