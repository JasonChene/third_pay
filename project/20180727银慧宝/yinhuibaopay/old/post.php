<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.php");
include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../moneyfunc.php");

#function
function curl_post($url, $data)
{ #POST访问
  $ch = curl_init();
  curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8', 'Content-Length:' . strlen($data)]);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
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
// $stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//appid
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
  "pay_memberid" => $pay_mid, 
  "pay_orderid" => $order_no,
  "pay_applydate" => date('Y-m-d H:i:s'),
  "pay_bankcode" => '',
  "pay_notifyurl" => $merchant_url,
  "pay_callbackurl" => $return_url,
  "pay_amount" => $mymoney,
  "pay_md5sign" => '',
  "pay_productname" => 'pay',
);

#变更参数设置
$form_url = 'http://www.yinhuibaopay.com/Pay_Index.html';
if (strstr($_REQUEST['pay_type'], "银联钱包")) {
  $scan = 'yl';
  $payType = $pay_type . "_yl";
  $bankname = $pay_type . "->银联钱包在线充值";
  $data['pay_bankcode'] = '911';
}else {
  $scan = 'wy';
  $payType = $pay_type . "_wy";
  $bankname = $pay_type . "->网银在线充值";
  $data['pay_bankcode'] = '907';
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
$noarr = array('pay_md5sign','pay_productname');
$signtext = '';
foreach ($data as $arr_key => $arr_val) {
  if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
    $signtext .= $arr_key . '=' . $arr_val . '&';
  }
}

$signtext = substr($signtext, 0, -1) . '&key=' . $pay_mkey;
$sign = strtoupper(md5($signtext));
$data['pay_md5sign'] = $sign; 

#跳轉方法
$form_data = $data;
$jumpurl = $form_url;

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl ?>" target="_self">
      <p>正在为您跳转中，请稍候......</p>
      <?php
      if (isset($form_data)) {
        foreach ($form_data as $arr_key => $arr_value) {
          ?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php 
    }
  } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>


?>
