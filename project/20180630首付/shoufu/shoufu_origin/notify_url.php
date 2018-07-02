<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

//write_log("notify");


#request方法
//write_log('request方法');
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
	echo ($key . "=" . $value . "<br>");
	//write_log($key . "=" . $value);
}


// $transDate = trim($_REQUEST['transDate']);
// $transTime = trim($_REQUEST['transTime']);
// $merchantNumber = trim($_REQUEST['merchantNumber']);
// $transAmount = trim($_REQUEST['transAmount']);
// $transNo = trim($_REQUEST['transNo']);
// $payWay = trim($_REQUEST['payWay']);
// $systemno = trim($_REQUEST['systemno']);
// $transStatus = trim($_REQUEST['transStatus']);
// $remark = trim($_REQUEST['remark']);
// $sign = trim($_REQUEST['sign']);



#########$params = array(':m_order' => 訂單號);###########
$params = array(':m_order' => $data['transNo']);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink("write1")->prepare($sql);
$stmt->execute($params);
$payInfo = $stmt->fetch();
$pay_mid = $payInfo['mer_id'];
$pay_mkey = $payInfo['mer_key'];
$mer_account = $row['mer_account'];
if ($pay_mid == "" || $pay_mkey == "") {
	echo ("非法提交参数");
	//write_log('非法提交参数');
	exit;
}

ksort($_REQUEST);
$noarr = array('sign');
foreach ($_REQUEST as $arr_key => $arr_val) {
	if (!in_array($arr_key, $noarr) && (!empty($arr_val) || $arr_val === 0 || $arr_val === '0')) {
		$signText .= $arr_key . '=' . $arr_val . '&';
	}
}


$signText = $signText . $pay_mkey;
echo ($signText . "<br>");
//write_log($signText);
$mysign = mb_strtoupper(md5($signText));
//write_log($mysign);
echo ($mysign . "<br>");

$mymoney = number_format($data['transAmount'], 2, '.', '');
if ($data['transStatus'] == "1") {
	if ($mysign == $data['sign']) {
		$result_insert = update_online_money($data['transNo'], $mymoney);
		if ($result_insert === -1) {
			echo ("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert === 0) {
			echo "SUCCESS at 0";
			exit;
		} else if ($result_insert === -2) {
			echo ("数据库操作失败");
			exit;
		} else if ($result_insert === 1) {
			echo "SUCCESS at 1";
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
