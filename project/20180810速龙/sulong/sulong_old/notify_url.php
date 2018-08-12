<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

	$merchant_code	= $_REQUEST["merchant_code"];		
	$notify_type = $_REQUEST["notify_type"];	
	$notify_id = $_REQUEST["notify_id"];
	$interface_version = $_REQUEST["interface_version"];
	$sign_type = $_REQUEST["sign_type"];
	$sign = $_REQUEST["sign"];	
	$order_no = $_REQUEST["order_no"];
	$order_time = $_REQUEST["order_time"];	
	$order_amount = $_REQUEST["order_amount"];	
	$extra_return_param = $_REQUEST["extra_return_param"];
	$trade_no = $_REQUEST["trade_no"];
	$trade_time = $_REQUEST["trade_time"];		
	$trade_status = $_REQUEST["trade_status"];
	$bank_seq_no = $_REQUEST["bank_seq_no"];

$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$parms=array(
	"merchant_code" => $merchant_code,
	"notify_type" => $notify_type,
	"notify_id" => $notify_id,
	"interface_version" => $interface_version,
	"sign_type" => $sign_type,
	"ovalue" => $ovalue,
	"order_no" => $order_no,
	"order_time" => $order_time,
	"order_amount" => $order_amount,
	"extra_return_param" => $extra_return_param,
	"trade_no" => $trade_no,
	"trade_time" => $trade_time,
	"trade_status" => $trade_status,
	"bank_seq_no" => $bank_seq_no
);
ksort($parms);
$signtext='';
foreach ($parms as $arr_key => $arr_value) {
	if($arr_value == "" || $arr_key == "sign_type"){
	}else{
		$signtext .= $arr_key . '=' . $arr_value . '&';
	}
}
$signtext2=substr($signtext, 0,-1);
	$dinpay_public_key = openssl_get_publickey($pay_account);	
	$flag = openssl_verify($signtext2,base64_decode($sign),$dinpay_public_key,OPENSSL_ALGO_MD5);
	
if ($trade_status == "SUCCESS") {
  if ( $flag == 1) {
		$result_insert = update_online_money($order_no, $order_amount);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");	
		}else if($result_insert == 0){
			echo ("SUCCESS");
		}else if($result_insert == -2){
			echo ("数据库操作失败");
		}else if($result_insert == 1){
			echo ("SUCCESS");
		} else {
			echo ("支付失败");
		}
	}else{
		echo '签名不正确！';
		exit;
	}
}else{
	echo '交易失败！';
	exit;
}

?>
