<?php
header("Content-type:text/html; charset=utf-8");
// include_once("../../../database/mysql.config.php");
include_once("../../../database/mysql.php");//现数据库的连接方式
include_once("../moneyfunc.php");
#预设时间在上海
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
function payType_bankname($scan,$pay_type){
  global $payType, $bankname;
  if($scan == "wy"){
    $payType = $pay_type . "_wy";
    $bankname = $pay_type . "->网银在线充值";
  }elseif($scan == "yl" || $scan == "ylfs"){
    $payType = $pay_type . "_yl";
    $bankname = $pay_type . "->银联钱包在线充值";
  }elseif($scan == "qq" || $scan == "qqfs"){
    $payType = $pay_type . "_qq";
    $bankname = $pay_type . "->QQ钱包在线充值";
  }elseif($scan == "wx" || $scan == "wxfs"){
    $payType = $pay_type . "_wx";
    $bankname = $pay_type . "->微信在线充值";
  }elseif($scan == "zfb" || $scan == "zfbfs"){
    $payType = $pay_type . "_zfb";
    $bankname = $pay_type . "->支付宝在线充值";
  }elseif($scan == "jd" || $scan == "jdfs"){
    $payType = $pay_type . "_jd";
    $bankname = $pay_type . "->京东钱包在线充值";
  }elseif($scan == "ylkj"){
    $payType = $pay_type . "_ylkj";
    $bankname = $pay_type . "->银联快捷在线充值";
  }elseif($scan == "bd" || $scan == "bdfs"){
    $payType = $pay_type . "_bd";
    $bankname = $pay_type . "->百度钱包在线充值";
  }else {
    echo('payType_bankname出错啦！');
    exit;
  }
}

#获取第三方资料(非必要不更动)
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
// $stmt = $mydata1_db->prepare($sql);
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);//现数据库的连接方式
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
$form_url = 'http://www.586pay.com/Pay';//接入提交地址

#第三方参数设置
$data =array(
  'fxid' => $pay_mid,
  'fxddh' => $order_no,
  'fxaction' =>"orderpay",
  'fxdesc' => "pay",
  'fxfee' => $mymoney,
  'fxnotifyurl' => $merchant_url,
  'fxbackurl' => $return_url,
  'fxpay' => "",
  'fxsign' => "",
  'fxip' => getClientIp(),
);

#变更参数设置
$scan = 'zfb';
$data['fxpay'] = 'alipay';

payType_bankname($scan,$pay_type);

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
$data["fxsign"] = md5($data["fxid"] . $data["fxddh"] . $data["fxfee"] . $data["fxnotifyurl"] . $pay_mkey);

#curl提交
$res = curl_post($form_url,$data);
$row = json_decode($res,1);
#跳转
if ($row['status'] != '1') {
  echo  '错误代码:' . $row['status']."<br>";
  echo  '错误讯息:' . $row['error']."<br>";
  exit;
}else {
  if(_is_mobile()){
    $jumpurl = $row['payurl'];
  }else{
    $jumpurl = '../qrcode/qrcode.php?type='.$scan.'&code=' .QRcodeUrl($row['payurl']);
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