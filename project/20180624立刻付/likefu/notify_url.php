<?php session_start(); ?>
<?php
//include_once("../config.php");
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$orderstatus = $_GET["orderstatus"];
$ordernumber = $_GET["ordernumber"];
$paymoney = $_GET["paymoney"];
$sign = $_GET["sign"];
$attach = $_GET["attach"];

// #接收资料
// #request方法
// $data = array();
// foreach ($_REQUEST as $key => $value) {
// 	$data[$key] = $value;
// 	write_log($key . "=" . $value);
// }

$params = array(':m_order' => $ordernumber);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("read1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$payType = substr($row['operator'], 0, strripos($row['operator'], "_"));

$params = array(':pay_type' => $payType);
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

$partner = $pay_mid;//商户ID
$Key = $pay_mkey;//商户KEY
$signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney, $Key);
if ($sign == md5($signSource)) {
	//校验通过开始处理订单
	if ($orderstatus == "1") {
		$result_insert = update_online_money($ordernumber, $paymoney);
		if ($result_insert == -1) {
			echo ("ok");
			exit;
		} else if ($result_insert == 0) {
			echo ("ok");
			exit;
		} else if ($result_insert == -2) {
			echo ("ok");
			exit;
		} else if ($result_insert == 1) {
			echo ("ok");
			exit;
		} else {
			echo ("ok");
			exit;
		}
		echo ("ok");
		exit;
	}
	echo ("ok");
	exit;
} else {
	echo ("Md5CheckFail"); //MD5校验失败，订单信息不显示
	exit;
}
?>