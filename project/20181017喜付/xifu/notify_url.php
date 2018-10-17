<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");



$body = trim($_REQUEST['body']);
$buyer_id = trim($_REQUEST['buyer_id']);
$gmt_create = trim($_REQUEST['gmt_create']);
$is_success = trim($_REQUEST['is_success']);
$is_total_fee_adjust = trim($_REQUEST['is_total_fee_adjust']);
$notify_time = trim($_REQUEST['notify_time']);
$order_no = trim($_REQUEST['order_no']);
$total_fee = trim($_REQUEST['total_fee']);
$trade_status = trim($_REQUEST['trade_status']);
$signType = trim($_REQUEST['signType']);
$sign = trim($_REQUEST['sign']);

#########$params = array(':m_order' => 訂單號);###########
$params = array(':m_order' => $order_no);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$mer_account = $row['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
echo ("非法提交参数");
exit;
}

ksort($_REQUEST);
$noarr =array('sign','signType');
foreach ($_REQUEST as $arr_key => $arr_val) {
	if ( !in_array($arr_key, $noarr)) {
		$signText .= $arr_key.'='. $arr_val.'&' ;
	}
}


$signText = substr($signText,0,-1).$pay_mkey;
$mysign = strtoupper(sha1($signText));


$mymoney = number_format($total_fee, 2, '.', '');
if ($is_success == "T") {
	if ($mysign ==$sign ) {
		$result_insert = update_online_money($order_no,$mymoney);
			if ($result_insert === -1) {
				echo ("会员信息不存在，无法入账");
				exit;
			} else if ($result_insert === 0) {
				echo "success";
				exit;
			} else if ($result_insert === -2) {
				echo ("数据库操作失败");
				write_log('数据库操作失败');
				exit;
			} else if ($result_insert === 1) {
				echo "success";
				exit;
			} else {
				echo ("支付失败");
				exit;
			}
	} else {
		echo '签名不正确！';
		exit;
	}
} else {
	echo '交易失败！';
	exit;
}
?>
