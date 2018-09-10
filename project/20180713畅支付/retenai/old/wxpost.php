<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
//include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

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
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmpInfo = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  return $tmpInfo;
}
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
//$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶密钥
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
$data =array(
  'sign_type' => "md5",
  'mch_id' => $pay_mid,
  'mch_order' => $order_no,
  'amt' => (int)number_format($_REQUEST['MOAmount']*1000, 0, '.', ''),
  'remark' => "pay",
  'created_at' => time(),
  'client_ip' => getClientIp(),
  'notify_url' => $merchant_url,
  'sign' => "",
  'mch_key' => $pay_mkey
);
#变更参数设置

if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $scan = 'jd';
  $form_url ='https://n-sdk.retenai.com/api/v1/jd_qrcode.api';
  $bankname = $pay_type."->京东钱包在线充值";
  $payType = $pay_type."_jd";
  if (_is_mobile()) {
    $form_url ='https://n-sdk.retenai.com/api/v1/jd_wap.api';
  }
}else {
  $scan = 'wx';
  $form_url ='https://n-sdk.retenai.com/api/v1/wx_qrcode.api';
  $payType = $pay_type."_wx";
  $bankname = $pay_type . "->微信在线充值";
  if (_is_mobile()) {
    $form_url ='https://n-sdk.retenai.com/api/v1/wx_h5.api';
  }
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
$noarr =array('sign');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext,0,-1);
$data['sign'] = md5($signtext);
unset($data['mch_key']);
#curl提交
$res = curl_post($form_url,$data);
$row = json_decode($res,1);
if ($row['code'] != '1') {
  echo  '错误代码:' . $row['code']."<br>";
  echo  '错误讯息:' . $row['msg']."<br>";
  exit;
}else {
  if (_is_mobile()) {
    $jumpurl = $row['data']['pay_info'];
  }else {
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' .QRcodeUrl($row['data']['code_url']);
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
      <?php
      if(isset($form_data)){
        foreach ($form_data as $arr_key => $arr_value) {
      ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php }} ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
