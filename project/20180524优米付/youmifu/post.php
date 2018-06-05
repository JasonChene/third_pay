<?php
header("Content-type:text/html; charset=UTF-8");
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

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wx_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wx_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}

#固定参数设置
$form_url = 'http://cashier.youmifu.com/cgi-bin/netpayment/pay_gate.cgi';  //提交地址
$top_uid = $_REQUEST['top_uid'];
$order_no = getOrderNo();
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', '');

#第三方参数设置
$data = array(
  "apiName" => "WEB_PAY_B2C",//接口名字 WAP方式：“WAP_PAY_B2C”（手机支付）WEB方式：“WEB_PAY_B2C”（pc浏览器）
  "apiVersion" => "1.0.0.1",//接口版本 取值：“1.0.0.1”
  "platformID" => $pay_mid,//商户(合作伙伴)ID 由支付系统统一分配
  "merchNo" => $pay_mid,//商户账号 由支付系统统一分配
  "orderNo" => $order_no,//商户订单号
  "tradeDate" => date("Ymd"),//交易日期
  "amt" => $mymoney,//订单金额 保留2位小数，单位：元
  "merchUrl" => $merchant_url,//支付结果通知地址
  "merchParam" => "abcd",//商户参数
  "tradeSummary" => "iphone1",//交易摘要
  "customerIP" => getClientIp(),//客户端IP
  "signMsg" => '',//签名 不进行签名
  "bankCode" => '',//银行代码 不进行签名
  "choosePayType" => ''//选择支付方式 不进行签名
);

#变更参数设置
$scan = "wy";
$data['bankCode'] = $_REQUEST['bank_code'];
$data['choosePayType'] = "1"; //1.网银
$payType = $pay_type . "_wy";
$bankname = $pay_type . "->网银在线充值";
if(strstr($pay_type, "银联快捷")){
    unset($data['bankCode']);
    $scan = "ylkj";
    $data['choosePayType'] = "12"; //12.平台快捷支付
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
}elseif (strstr($pay_type, "银联钱包")) {
    unset($data['bankCode']);
    $scan = "yl";
    $data['choosePayType'] = "17"; //17.银联扫码
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
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
$noarr =array('signMsg','bankCode','choosePayType');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( !in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext, 0,-1).$pay_mkey;
$sign = md5($signtext);
$data['signMsg'] = $sign;


#curl获取响应值

#form表单 post跳转
?>
<html>
 <head>
   <title>跳转......</title>
   <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
 </head>
 <body>
   <form name="dinpayForm" method="post" id="frm1" action="<?php echo $form_url?>" target="_self">
   <p>正在为您跳转中，请稍候......</p>
   <?php foreach ($data as $arr_key => $arr_value) { ?>
     <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
   <?php } ?>
   </form>
   <script language="javascript">
     document.getElementById("frm1").submit();
   </script>
  </body>
</html>
