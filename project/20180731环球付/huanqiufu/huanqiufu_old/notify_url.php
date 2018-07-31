<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../moneyfunc.php");
$total_fee = $_REQUEST["total_fee"];
$order_no = $_REQUEST["out_order_no"];
$trade_no = $_REQUEST["trade_no"];
$trade_status = $_REQUEST["trade_status"];
$t_pay_type = $_REQUEST["pay_type"];
$sign = $_REQUEST["sign"];

// write_log("notify");
// foreach ($_REQUEST as $key11 => $value11) {
//   write_log($key11."=".$value11);
// }

$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mydata1_db->prepare($sql);//原数据库的连接方式
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}
$parms = array(
	"out_order_no" => $order_no,
	"total_fee" => $total_fee,
	"trade_status" => $trade_status
);
$signtext = '';
$noarr = array('sign', 'trade_no', 'pay_type');
foreach ($parms as $arr_key => $arr_value) {
	if (in_array($arr_key, $noarr) || $arr_value == "") {
	} else {
		$signtext .= $arr_value;
	}
}
$signtext2 = $signtext . $pay_account . $pay_mkey;

$mysign = md5($signtext2);

  // write_log("signtext=".$signtext2);
  // write_log("mysign=".$mysign);

if ($trade_status == "TRADE_SUCCESS") {
	if ($mysign == $sign) {
		$mymoney = number_format($total_fee, 2, '.', ''); //订单金额
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			echo ("success");
			exit;
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			echo ("success");
			exit;
		} else {
			echo ("支付失败");
			exit;
		}
	} else {
		echo ('签名不正确！');
		exit;
	}
} else {
	echo ("交易失败");
	exit;
}

?>
