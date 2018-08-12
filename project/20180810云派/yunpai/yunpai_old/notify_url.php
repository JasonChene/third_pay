<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");

	$pay_status = $_REQUEST["pay_status"];		
	$out_trade_no = $_REQUEST["out_trade_no"];//订单
	$trade_amount = $_REQUEST["trade_amount"];//金额
	$reserved = $_REQUEST["reserved"];
	$sign_type = $_REQUEST['sign_type'];
	$sign = $_REQUEST["sign"];


$params = array(':m_order' => $out_trade_no);
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
	"pay_status" => $pay_status,
	"out_trade_no" => $out_trade_no,
	"trade_amount" => $trade_amount,
	"reserved" => $reserved,
	"sign_type" => $sign_type
);
ksort($parms);
$signtext='';
foreach ($parms as $arr_key => $arr_value) {
  if($arr_value == ""){
  }else{
    $signtext .= $arr_key . '=' . $arr_value . '&';
  }
}
$signtext2=substr($signtext, 0,-1)."&key=".$pay_mkey;

$mysign = mb_strtoupper(md5($signtext2));

if ($pay_status == "1") {
  if ( $mysign == $sign ) {
  	$mymoney = number_format($trade_amount/100, 2, '.', ''); //订单金额
		$result_insert = update_online_money($out_trade_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");	
			exit;
		}else if($result_insert == 0){
			echo ("ok");
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ("ok");
			exit;
		} else {
			echo ("支付失败");
			exit;
		}
	}else{
		echo '签名不正确！';
		exit;
	}
}else{
	echo ("交易失败");
	exit;
}

?>
