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
$pay_mid = $row['mer_id'];//商户号 代付用
$pay_mkey = $row['mer_key'];//支付秘钥
$pay_account = $row['mer_account'];//支付key 商户辨识号
$return_url = $row['pay_domain'] . $row['zfb_returnUrl'];//return跳转地址
$merchant_url = $row['pay_domain'] . $row['zfb_synUrl'];//notify回传地址
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
  "trx_key" => $pay_account,
  "ord_amount" => number_format($_REQUEST['MOAmount'], 2, '.', ''), //订单金额 订单金额,单位：元，保留小数点后两位
  "request_id" => $order_no,//订单号
  "product_type" => '',//产品类型
  "request_time" => date('YmdHis'),//下单时间
  "goods_name" => 'iphone',//商品名称
  "request_ip" => getClientIp(),//请求IP
  "return_url" => $return_url,//页面通知地址
  "callback_url" => $merchant_url,//后台异步通知地址
  "remark" => $order_no,//备注
  "sign" => '',//签名
);
#变更参数设置
if (strstr($_REQUEST['pay_type'], "银联钱包")) {
    $form_url = 'http://gateway.idupay.cn/cnp/gateway';//提交地址
    $scan = "yl";
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联钱包在线充值";
    $data['product_type'] = '60103';//银联扫码支付	60103
    if(_is_mobile()){
      $data['product_type'] = '60203';//银联WAP支付	60203
    }
}elseif (strstr($_REQUEST['pay_type'], "银联快捷")) {
    $form_url = 'http://gateway.idupay.cn/b2c/gateway';//提交地址
    $scan = "ylkj";
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
    $data['product_type'] = '40103';//快捷支付	40103
    $data['bank_code'] = $_REQUEST['bank_code'];//银行编码
    $data['account_type'] = 'PRIVATE_DEBIT_ACCOUNT';//PRIVATE_DEBIT_ACCOUNT借记卡PRIVATE_CREDIT_ACCOUNT贷记卡
}else {
    $form_url = 'http://gateway.idupay.cn/b2c/gateway';//提交地址
    $scan = "wy";
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
    $data['product_type'] = '50103';//B2C支付	50103
    $data['bank_code'] = $_REQUEST['bank_code'];//银行编码
    $data['account_type'] = 'PRIVATE_DEBIT_ACCOUNT';//PRIVATE_DEBIT_ACCOUNT借记卡PRIVATE_CREDIT_ACCOUNT贷记卡

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
$signtext = substr($signtext,0,-1).'&secret_key='.$pay_mkey;
$data['sign'] = strtoupper(md5($signtext));
#curl获取响应值

$res = curl_post($form_url,http_build_query($data));
$row = json_decode($res,1);
#跳转
if ($row['rsp_code'] != '0000') {
  echo  '错误代码:' . $row['rsp_code']."\n";
  echo  '错误讯息:' . $row['rsp_msg']."\n";
  exit;
}else {
      if (_is_mobile() || $scan == 'ylkj' || $scan =='wy')  {
          $jumpurl = $row['data'];
      }else {
          $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code='.QRcodeUrl($row['data']);
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
    <form name="dinpayForm" method="get" id="frm1" action="<?php echo $jumpurl?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
