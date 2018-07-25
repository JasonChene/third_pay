<?php
header("Content-type:text/html; charset=utf-8");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");
include_once ("function.php");

$top_uid = $_REQUEST['top_uid'];

date_default_timezone_set('PRC');

if (function_exists("date_default_timezone_set")) {
  date_default_timezone_set("Asia/Shanghai");
}


//獲取第三方的资料
$pay_type = $_REQUEST['pay_type'];
$params = array(':pay_type' => $_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];//商戶金鑰
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'] . $row['qq_returnUrl'];
$merchant_url = $row['pay_domain'] . $row['qq_synUrl'];
if ($pay_mid == "" || $pay_mkey == "") {
  echo "非法提交参数";
  exit;
}
$form_url = 'http://api.xinfuokpay.com/trade/pay';  //提交地址
$mchid = $pay_mid;  //商戶號
$src_code=$pay_account;//商户标识
$total_fee = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$out_trade_no = date("YmdHis") . substr(microtime(), 2, 5) . rand(1, 9);  //随机生成商户订单编号
$callbackurl = $merchant_url;  //异步
 $finish_url = $return_url;//同步
$time_start=date("YmdHis");//第三方要的时间
$goods_name="abcd";//商品名称
// H5選擇type
if (_is_mobile()) {
  $trade_type = '40107';//QQ WAP
} else {
  $trade_type = '40104';//QQ扫码
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
ksort($parms);
$sign = get_md5($parms,$pay_mkey);
$parms['sign']=$sign;

  $payT = $pay_type."_qq";
  $bankname = $pay_type . "->QQ钱包在线充值";

//確認訂單有無重複， function在 moneyfunc.php 裡
$mymoney = number_format($_REQUEST['MOAmount'], 2, '.', ''); //订单金额
$result_insert = insert_online_order($_REQUEST['S_Name'], $out_trade_no, $mymoney, $bankname, $payT, $top_uid);
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
    if(_is_mobile()){
      header("location:".$purl);
    }else{
      header("location:".'../qrcode/qrcode.php?type=qq&code='.$purl );
    } 
  }else{
    echo ("第三方错误代码：".$return_info->respcd.'<br>');
    echo ("第三方错误讯息：".$return_info->respmsg);
  }
}else{
  echo '发生错误';
}

?>
