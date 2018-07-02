<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$code = trim($_REQUEST['code']);
$msg = trim($_REQUEST['msg']);
$sign = trim($_REQUEST['sign']);
$order_sn = trim($_REQUEST['order_sn']);
$down_sn = trim($_REQUEST['down_sn']);
$status = trim($_REQUEST['status']);
$amount = trim($_REQUEST['amount']);
$fee = trim($_REQUEST['fee']);
$trans_time = trim($_REQUEST['trans_time']);
$remark = trim($_REQUEST['remark']);


$parms = array();
foreach ($_REQUEST as $key => $value) {
	if ($key == "sign" || $key == "code" || $key == "msg") {
		continue;
	} else if ($value === "") {
		continue;
	} else {
		$parms[$key] = $value;
	}
}

#########$params = array(':m_order' => 訂單號);###########
$params = array(':m_order' => $down_sn);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$pay_account = $payInfo['mer_account'];

if ($pay_mid == "" || $pay_mkey == "") {
	echo "非法提交参数";
	exit;
}

ksort($parms);
$strRet = "";
foreach ($parms as $key => $value) {
	if ($value === "") {
		continue;
	} if ($key == "sign" || $key == "code" || $key == "msg" ) {
		continue;
	}
	$strRet .= "$key=" . $value . "&";
}
$strRet .= 'key='.$pay_mkey;

$mysign = strtolower(md5($strRet));
// write_log($strRet);

################if(訂單狀態成功)####################
if ($code == "0000") {
  if ($sign == $mysign) {
    ###############update_online_money(商戶訂單號,支付金額)##################
		$result_insert = update_online_money($down_sn, $amount);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			// write_log("会员信息不存在，无法入账");
		} else if ($result_insert == 0) {
			echo ("SUCCESS");
			// write_log("success");
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			// write_log("数据库操作失败");
		} else if ($result_insert == 1) {
			echo ("SUCCESS");
			// write_log("success");
		} else {
			echo ("支付失败");
			// write_log("支付失败");
		}
	} else {
		echo '交易失败！';
		// write_log("交易失败！");
		exit;
	}
} else {
	echo '签名不正确！';
	// write_log("签名不正确！");
	exit;
}

?>
