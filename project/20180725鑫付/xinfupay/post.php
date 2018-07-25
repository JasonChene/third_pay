<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
include_once ("function.php");
function QRcodeUrl($code){
  if(strstr($code,"&")){
    $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
  }else{
    $code2=$code;
  }
  return $code2;
}
$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}

//獲取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $pay_type);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//商戶金钥
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['wy_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['wy_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}


$form_url = 'http://api.xinfuokpay.com/trade/pay';  //提交地址
$mchid = $pay_mid; 	//商戶號
$src_code=$pay_account;//商户标识
$total_fee = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$out_trade_no = getOrderNo();  //随机生成商户订单编号
$callbackurl = $merchant_url;  //异步
 $finish_url = $return_url;//同步
$time_start=date("YmdHis");//第三方要的时间
$goods_name="abcd";//商品名称
$ylscan = false;
if (strstr($pay_type, "银联钱包"))
{
    $ylscan = true;
}
$ylkjscan = false;
if (strstr($pay_type, "银联快捷"))
{
    $ylkjscan = true;
}

// H5選擇type
if ($ylscan == true) {
  $trade_type = '30104';//银联錢包扫码
  if(_is_mobile()){
    $trade_type = '30107';//银联錢包H5
    if(empty($_REQUEST['accoutNo'])){
      ?>
<html>
  <head>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo './card.php' ?>" target="_self">
      <?php foreach ($_REQUEST as $arr_key => $arr_value) {?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
      <?php
      exit;
    }
    $extendtext = array (
      "accoutNo" => $_REQUEST['accoutNo']
    );
    $extend=json_encode($extendtext);
  }
}elseif ($ylkjscan == true && _is_mobile()) {
    $trade_type = '30107';//银联錢包H5
    if(empty($_REQUEST['accoutNo'])){
      ?>
<html>
  <head>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo './card.php' ?>" target="_self">
      <?php foreach ($_REQUEST as $arr_key => $arr_value) {?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
      <?php
      exit;
    }
    $extendtext = array (
      "accoutNo" => $_REQUEST['accoutNo']
    );
    $extend=json_encode($extendtext);
}else{
  $trade_type = '80103';//网关支付
  $extendtext=array(
    "bankName" => $_REQUEST['bank_code'],
    "cardType" => "借记卡"
  );
  $extend=json_encode($extendtext);
}
$parms = array(
  "src_code" => $src_code,//商户唯一标识
  "out_trade_no" => $out_trade_no,//订单号
  "total_fee" => $total_fee*100,//金额
  "time_start" => $time_start,//第三方要的时间
  "goods_name" => $goods_name,//商品名称
  "trade_type" => $trade_type,//支付类型
  "finish_url"=> $finish_url ,//同步地址
  "mchid" => $mchid//商户号
);
if($ylkjscan && !_is_mobile()){
  $form_url = 'http://api.xinfuokpay.com/pay/fast/sign';  //提交地址
  unset($parms['mchid']);
  unset($parms['trade_type']);
  if(empty($_REQUEST['bankName']) || empty($_REQUEST['cardType']) || empty($_REQUEST['accoutNo']) || empty($_REQUEST['accountName']) || empty($_REQUEST['idNumber']) || empty($_REQUEST['Mobile']) ){
    ?>
<html>
  <head>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo './card.php' ?>" target="_self">
      <?php foreach ($_REQUEST as $arr_key => $arr_value) {?>
      <input type="hidden" name="<?php echo $arr_key; ?>" value="<?php echo $arr_value; ?>" />
      <?php } ?>
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
    <?php
    exit;
  }
  $parms['mch_id'] = $mchid;//快捷用商户号
  $parms['bankName'] = $_REQUEST['bankName'];
  $parms['cardType'] = $_REQUEST['cardType'];
  $parms['accoutNo'] = $_REQUEST['accoutNo'];
  $parms['accountName'] = $_REQUEST['accountName'];
  $parms['idType'] = '身份证';
  $parms['idNumber'] = $_REQUEST['idNumber'];
  $parms['Mobile'] = $_REQUEST['Mobile'];
}
if($trade_type != '30104' && !($ylkjscan == true && !_is_mobile())){
  $parms['extend']=$extend;
}
ksort($parms);
$sign = get_md5($parms,$pay_mkey);
$parms['sign']=$sign;
if($ylkjscan){
  $payType = $pay_type . "_ylkj";
  $bankname = $pay_type . "->银联快捷在线充值";
}elseif($ylscan){
  $bankname = $pay_type."->银联钱包在线充值";
  $payType = $pay_type."_yl";
}else{
  $bankname = $pay_type."->网银在线充值";
  $payType = $pay_type."_wy";
}

//確認訂單有無重複， function在 moneyfunc.php 裡
$result_insert = insert_online_order($_REQUEST['S_Name'], $out_trade_no, $total_fee, $bankname, $payType, $top_uid);
if ($result_insert == -1) {
  echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
  exit;
} else if ($result_insert == -2) {
  echo "订单号已存在，请返回支付页面重新支付";
  exit;
}

$res=http($form_url,$parms);

if($res['http_code'] == 200){
  $return_info = json_decode($res['http_data']);//物件
  if($return_info->respcd == "0000"){
    $purl=$return_info->data->pay_params;//物件网址位置
    if($trade_type == '30104'){
      $jumpurl = '../qrcode/qrcode.php?type=yl&code='.QRcodeUrl($purl);
    }else{
      $jumpurl = $purl;
    }
  }else{
    echo ("第三方错误代码：".$return_info->respcd.'<br>');
    echo ("第三方错误讯息：".$return_info->respmsg);
    exit;
  }
}else{
  echo '发生错误';
  exit;
}


?>
<html>
  <head>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form name="dinpayForm" method="post" id="frm1" action="<?php echo $jumpurl?>" target="_self">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
