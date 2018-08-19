<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
date_default_timezone_set('PRC');
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
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];//商户号
$pay_mkey = $row['mer_key'];//商戶密钥
$pay_account = $row['mer_account'];//商户支付Key
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
$form_url ='https://api.shaimeixiong.com/api/receive?type=form';
#第三方参数设置
$data =array(
  'type' => "form",//接口调用方式
  'merchantId' => $pay_mid,//商户uid
  'money' => number_format($_REQUEST['MOAmount'], 0, '.', ''),//订单金额
  'timestamp' => time(),//时间戳
  'notifyURL' => $merchant_url,//回调地址
  'returnURL' => $return_url,//支付结果展示地址
  'merchantOrderId' => $order_no,//商户自定义订单号
  'sign' => "",//MD5小写签名
  'paytype' => ""//支付类型
);

#变更参数设置
$scan = 'qq';
$payType = $pay_type."_qq";
$bankname = $pay_type . "->QQ钱包在线充值";
$data['paytype'] = "QQ";//qq掃碼


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

$signtext = '';
$signtext .= $data['money'].'&';
$signtext .= $data['merchantId'].'&';
$signtext .= $data['notifyURL'].'&';
$signtext .= $data['returnURL'].'&';
$signtext .= $data['merchantOrderId'].'&';
$signtext .= $data['timestamp'].'&';
$signtext .= $pay_mkey;

$data['sign'] = md5($signtext);

#直接表單post
$form_data = $data;
$jumpurl = $form_url;
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
