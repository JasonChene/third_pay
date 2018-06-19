<?php session_start(); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
$top_uid = $_REQUEST['top_uid'];

if(function_exists("date_default_timezone_set"))
{
	date_default_timezone_set("Asia/Shanghai");
}
//获取第三方的资料
$params = array(':pay_type'=>$_REQUEST['pay_type']);
$sql = "select t.pay_name,t.mer_id,t.mer_key,t.mer_account,t.pay_type,t.pay_domain,t1.wy_returnUrl,t1.wx_returnUrl,t1.zfb_returnUrl,t1.wy_synUrl,t1.wx_synUrl,t1.zfb_synUrl from pay_set t left join pay_list t1 on t1.pay_name=t.pay_name where t.pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_mid = $row['mer_id'];
$pay_mkey = $row['mer_key'];
$pay_account = $row['mer_account'];
$return_url = $row['pay_domain'].$row['wx_returnUrl'];
$merchant_url = $row['pay_domain'].$row['wx_synUrl'];

$pay_type = $row['pay_type'];
if($pay_mid == "" || $pay_mkey == "")
{
	echo "非法提交参数";
	exit;
}
$orderno = date("YmdHis").substr(microtime(),2,5).rand(1,9);//流水号
$paytype = 20; //20代表网银支付，22代表支付宝支付，30代表微信支付
$paycode = "";
$usercode = $pay_mid;//商户号
$value = number_format($_REQUEST['MOAmount'],2,".","");//订单金额
$notifyurl = $merchant_url; //商户异步通知地址
$returnUrl = $return_url;//服务器底层通知地址
$dateis = date('is');
$remark = $dateis.md5($pay_mid.$dateis);//订单附加消息
$datetime = date("YmdHis",time());//交易时间
$goodsname = "网购商品";//产品名称

$Md5key=$pay_mkey;//md5密钥（KEY）

//MD5签名格式
$Signature=md5("usercode=".$usercode."&orderno=".$orderno."&datetime=".$datetime."&paytype=".$paytype."&value=".$value."&notifyurl=".$notifyurl."&returnurl=".$returnUrl."&key=".$Md5key);

$payUrl="https://api.yecimo.com/trade/pay";//借贷混合


$bankname = $pay_type."->网银在线充值";
$payType = $pay_type."_wy";

if (strstr($_REQUEST['pay_type'],"银联钱包")){
  $payType = $pay_type . "_yl";
  $bankname = $pay_type . "->银联钱包在线充值";
}
$result_insert = insert_online_order($_REQUEST['S_Name'] , $orderno , $value,$bankname,$payType,$top_uid);

if ($result_insert == -1)
{
	echo "会员信息不存在，无法支付，请重新登录网站进行支付！";
	exit;
}
else if ($result_insert == -2)
{
	echo "订单号已存在，请返回支付页面重新支付";
	exit;
}
$src_code = $pay_account;
$out_trade_no = $orderno;
$total_fee = $value * 100;
$time_start = $datetime;
$goods_name = "pay_online";
$trade_type = "80103";
$finish_url = $merchant_url;
$out_mchid = "";
$mchid = $pay_mid;
$time_expire = "";
$fee_type = "";
$goods_detail = "";
$openid = "";
$auth_code = "";
$limit_pay = "";
$post_data = '';
$post_data = array(
	'src_code' => $src_code,
	'out_trade_no' => $out_trade_no,
	'total_fee' => $total_fee,
	'time_start' => $time_start,
	'goods_name' => $goods_name,
	"trade_type" => $trade_type,
	'finish_url' =>$finish_url,
	'mchid' =>$mchid
);
$extend = array();
if (strstr($_REQUEST['pay_type'],"银联钱包")){
	$extend['bankName'] = '银联通道';
	$extend['cardType'] = '借记卡';//只支援這個
}else {
	$extend['bankName'] = $_REQUEST['bank_code'];
	$extend['cardType'] = '借记卡';
}

$post_data['extend']= json_encode($extend);
ksort($post_data);
$a = '';
foreach ($post_data as $x => $x_value) {
	$a = $a . $x . "=" . iconv('UTF-8', 'GB2312', $x_value) . "&";
}

$sign = strtoupper(md5($a."key=".$pay_mkey));

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$payUrl);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $a."sign=".$sign);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);
$PayDate=json_decode($response);

$respcd = $PayDate ->respcd;

if($respcd == '0000'){
$pay_params	= $PayDate ->data->pay_params;
header("location:" .$pay_params);
exit();
}else {
	echo $PayDate ->respmsg;
}


?>
