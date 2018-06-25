<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
//write_log("notify");
$orderid = trim($_REQUEST['orderid']);
$opstate = trim($_REQUEST['opstate']);
$ovalue = trim($_REQUEST['ovalue']);
$sign = trim($_REQUEST['sign']);
$sysorderid = trim($_REQUEST['sysorderid']);
$systime = trim($_REQUEST['systime']);
$attach = trim($_REQUEST['attach']);
$msg = trim($_REQUEST['msg']);


$params = array(':m_order' => $orderid);
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


$signText = "orderid=" . $orderid . "&opstate=" . $opstate . "&ovalue=" . $ovalue . "&time=" . $systime . "&sysorderid=" . $sysorderid . $pay_mkey;


$mysign = strtolower(md5($signText));
//write_log('signText = ' . $signText);
//write_log('mysign = ' . $mysign);



if ($opstate == "0") {
	if ($sign == $mysign) {
		$result_insert = update_online_money($orderid, $ovalue);
		if ($result_insert == -1) {

			echo ("会员信息不存在，无法入账");
			//write_log("会员信息不存在，无法入账");

		} else if ($result_insert == 0) {

			echo ("0");
			//write_log("0");
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			//write_log("数据库操作失败");
		} else if ($result_insert == 1) {
			echo ("1");
			//write_log("1");
		} else {
			echo ("支付失败");
			//write_log("支付失败,请重新支付！");
		}
	} else {
		//簽名不對
		echo '签名不正确！';
		//write_log("签名不正确！");
		exit;
	}
} else {
//交易不成功
	echo '交易失败！';
	//write_log("交易失败！");
	exit;
}

?>
