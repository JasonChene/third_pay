<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.php");
include_once("../moneyfunc.php");

$reCode = trim($_REQUEST["reCode"]);
$trxMerchantNo = trim($_REQUEST["trxMerchantNo"]);
$trxMerchantOrderno = trim($_REQUEST["trxMerchantOrderno"]);
$result = trim($_REQUEST["result"]);
$productNo = trim($_REQUEST["productNo"]);
$memberGoods = trim($_REQUEST["memberGoods"]);
$amount = trim($_REQUEST["amount"]);
$sign = trim($_REQUEST["hmac"]);

$params = array(':m_order' => $trxMerchantOrderno);
$sql = "select operator from k_money where m_order=:m_order";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
$stmt->execute($params);
$row = $stmt->fetch();

//获取该订单的支付名称
$pay_type = substr($row['operator'], 0, strripos($row['operator'], "_"));
$params = array(':pay_type' => $pay_type);
$sql = "select * from pay_set where pay_type=:pay_type";
$stmt = $mysqlLink->sqlLink('read1')->prepare($sql);
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
	"reCode" => $reCode,
	"trxMerchantNo" => $trxMerchantNo,
	"trxMerchantOrderno" => $trxMerchantOrderno,
	"result" => $result,
	"productNo" => $productNo,
	"memberGoods" => $memberGoods,
	"amount" => $amount
);

$signtext = '';
foreach ($parms as $arr_key => $arr_value) {
	if ($arr_value == "" || $arr_key == "signtype") {
	} else {
		$signtext .= $arr_key . '=' . $arr_value . '&';
	}
}
$signtext2 = substr($signtext, 0, -1) . "&key=" . $pay_mkey;
$mysign = mb_strtolower(md5($signtext2));

if ($reCode == 1) {
	if ($mysign == $sign) {
		$result_insert = update_online_money($trxMerchantOrderno, $amount);
		if ($result_insert == -1) {
			echo ("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			echo ("SUCCESS");
			exit;
		} else if ($result_insert == -2) {
			echo ("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			echo ("SUCCESS");
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
	echo ("交易失败");
	exit;
}

?>
