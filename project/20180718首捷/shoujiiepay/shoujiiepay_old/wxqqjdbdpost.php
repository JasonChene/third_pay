<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

#function

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
  "Ver" => "1.0", 
  "partner" => $pay_mid,//商户号
  "paymoney" => $mymoney,//订单金额：单位/元//支付方式
  "ordernumber" => $order_no,//商户流水号
  "paytype" => 'weixin',//通道
  "bankcode" => $_REQUEST['bank_code'],//银行
  "notifyurl" => $merchant_url,//异步
  "returnurl" => $return_url,//同步

);
#变更参数设置
$form_url = 'http://www.shengxinpay.com/payapi';//提交地址
$scan = 'wx';
$payType = $pay_type . "_wx";
$bankname = $pay_type . "->微信在线充值";
if(_is_mobile()){
  $data['paytype'] = 'wxwap';
}
if(strstr($pay_type, "微信反扫")){
  $data['paytype'] = 'weixinrev';
}
if (strstr($pay_type, "qq钱包") || strstr($pay_type, "QQ钱包")) {
  $scan = 'qq';
  $data['paytype'] = 'qq';
  if(_is_mobile()){
    $data['paytype'] = 'qqwap';
  }
$payType = $pay_type . "_qq";
$bankname = $pay_type . "->QQ钱包在线充值";
}elseif (strstr($pay_type, "京东钱包")) {
  $scan = 'jd';
  $data['paytype'] = 'jd';
  if(_is_mobile()){
    $data['paytype'] = 'jdwap';
  }
$payType = $pay_type . "_jd";
$bankname = $pay_type . "->京东钱包在线充值";
}elseif (strstr($pay_type, "百度钱包")) {
  $scan = 'bd';
  $data['paytype'] = 'baidu';
$payType = $pay_type . "_bd";
$bankname = $pay_type . "->百度钱包在线充值";
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
$yesarr = array('Ver','partner','ordernumber','paymoney','notifyurl','returnurl');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if ( in_array($arr_key, $yesarr) && (!empty($arr_val) || $arr_val ===0 || $arr_val ==='0') ) {
		$signtext .= $arr_key.'='.$arr_val.'&';
	}
}
$signtext = substr($signtext,0,-1).'&'.$pay_mkey;
$sign = md5($signtext);
$data['sign'] = $sign;

#curl获取响应值

#跳转

#跳轉方法

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


