<?php
include_once("config.php");

/**************************请求参数**************************/

$service		 = $_POST['WIDpay_sid'];  /* 交易方式，业务代码*/
$body			 = $_POST['WIDbody'];		  /* 商品名 */	
$out_trade_no    = $_POST['WIDout_trade_no']; /* 商户订单号*/       		 
$total_fee		 = $_POST['WIDtotal_fee'];		  /* 总金额,如：88.88 */
$bank = $_POST['bank'];
//记录订单，为了扫码返回验证测试用，实际项目不能这样操作
get_dingdan($out_trade_no);


/* 需要签名的字段 */
$sign_arr = array(
	'service'      => $service,
	'out_trade_no' => $out_trade_no,
	'total_fee'    => $total_fee,
	'body'         => $body,
	'charset'      => $config['charset'],
	'mch_create_ip'=> $_SERVER['REMOTE_ADDR'],
	'partner'      => $config['partner'],
	'return_url'   => $config['return_url'],
	'notify_url'   => $config['notify_url'],
	'nonce_str'    => rand(1111,9999).time(),
    'bank'         => $bank,
);
if($service=="wangyin.wap" && empty($bank)){
    exit("银行缩写不能为空");
}
//MD5签名
$sign_md5 = md5_sign($sign_arr,$config['key']);
$sign_arr['sign'] = $sign_md5;

$payurl = $config['pay_url'].'?'.arr_url($sign_arr);
if($service == 'alipay.ma'){ //支付宝扫码
	get_ma($payurl,$out_trade_no,'支付宝');
}elseif($service == 'wxpay.ma'){ //微信扫码
	get_ma($payurl,$out_trade_no,'微信');
}elseif($service == 'qqpay.ma'){ //QQ钱包扫码
	get_ma($payurl,$out_trade_no,'QQ钱包');
}elseif($service == 'wangyin.ma'){
    $json = file_get_contents($payurl);
    $arr = json_decode($json,1);
    header("Location:".$arr['code_url']); exit;
}elseif ($service=="yinlian.ma") {
	get_ma($payurl,$out_trade_no,'银联');
}elseif ($service=="jdpay.ma") {
	get_ma($payurl,$out_trade_no,'京东');
}elseif($service=="wangyin.wap"){
	//远程获取二维码
	$json = file_get_contents($payurl);

	$arr = json_decode($json,1);

	 header("Location:".$arr['pay_url']); exit;
} else{
    $json = file_get_contents($payurl);

    $arr = json_decode($json,1);

    header("Location:".$arr['pay_url']); exit;
}
?>