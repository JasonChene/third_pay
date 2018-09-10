<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

	$status	= $_REQUEST["status"];
	$customerid = $_REQUEST["customerid"];
	$sdpayno = $_REQUEST["sdpayno"];
	$sdorderno = $_REQUEST["sdorderno"];
	$total_fee = $_REQUEST["total_fee"];
	$paytype = $_REQUEST["paytype"];
	$remark = $_REQUEST["remark"];
	$sign = $_REQUEST["sign"];

$params = array(':m_order' => $sdorderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
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
	"customerid" => $customerid,
	"status" => $status,
	"sdpayno" => $sdpayno,
	"sdorderno" => $sdorderno,
	"total_fee" => $total_fee,
	"paytype" => $paytype
);


$signtext='';
foreach ($parms as $arr_key => $arr_value) {
  if($arr_value == ""){
  }else{
    $signtext .= $arr_key . '=' . $arr_value . '&';
  }
}
$signtext2=$signtext.$pay_mkey;
$mysign = md5($signtext2);


if ($status == "1") {
  if ( $mysign == $sign) {
  	$mymoney = number_format($total_fee, 2, '.', ''); //订单金额
		$result_insert = update_online_money($sdorderno, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			exit;
		}else if($result_insert == 0){
			echo ("success");
			exit;
		}else if($result_insert == -2){
			echo ("数据库操作失败");
			exit;
		}else if($result_insert == 1){
			echo ("success");
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
