<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
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
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
function HmacMd5($data,$key)
{

$key = iconv("GB2312","UTF-8",$key);
$data = iconv("GB2312","UTF-8",$data);

$b = 64;
if (strlen($key) > $b) {
$key = pack("H*",md5($key));
}
$key = str_pad($key, $b, chr(0x00));
$ipad = str_pad('', $b, chr(0x36));
$opad = str_pad('', $b, chr(0x5c));
$k_ipad = $key ^ $ipad ;
$k_opad = $key ^ $opad;

return md5($k_opad . pack("H*",md5($k_ipad . $data)));
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
$form_url ='http://gyzf.in/GateWay/ReceiveBank.aspx';
#第三方参数设置
$data = array(
  "p0_Cmd" => "Buy",//固定值“Buy”
  "p1_MerId" => $pay_mid,//商户编号
  "p2_Order" => $order_no,//商户订单号
  "p3_Amt" => $mymoney,//支付金额
  "p4_Cur" => "CNY",//交易币种
	"r5_Pid" => "",//商品名称
	"p6_Pcat" => "",//商品种类
	"p7_Pdesc" => "",//商品描述
  "p8_Url" => $merchant_url,//后台通知地址
	"p9_SAF" => "",//送货地址
	"pd_FrpId" => "",//通道编码
  "pr_NeedResponse" => "1",//应答机制
	"hmac" => ""//签名数据
);
#变更参数设置

if (strstr($_REQUEST['pay_type'], "京东钱包")) {
  $scan = 'jd';
  $data['pd_FrpId'] = 'jdpay';
  if (_is_mobile()) {
    $data['pd_FrpId'] = 'jdwap';
  }
  $bankname = $pay_type."->京东钱包在线充值";
  $payType = $pay_type."_jd";
}elseif (strstr($_REQUEST['pay_type'], "微信反扫")) {
  $scan = 'wxf';
  $data['pd_FrpId'] = 'wxqr';
  $payType = $pay_type."_wx";
  $bankname = $pay_type . "->微信在线充值";
}else {
  $scan = 'wx';
  $payType = $pay_type."_wx";
  $bankname = $pay_type . "->微信在线充值";
  $data['pd_FrpId'] = 'wxcode';
  if (_is_mobile()) {
    $data['pd_FrpId'] = 'wxwap';
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
$noarr =array('hmac');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) ) {
		$signtext .= $arr_val;
	}
}
$hmac = HmacMd5($signtext,$pay_mkey);
$data['hmac'] = $hmac;

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url?>" target="_self">
    <p>正在为您跳转中，请稍候......</p>
    <?php foreach ($data as $arr_key => $arr_value) {?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
    <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
   </body>
 </html>
