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
			$message = ("会员信息不存在，无法入账");
		} else if ($result_insert == 0) {
			$message = ("SUCCESS");
		} else if ($result_insert == -2) {
			$message = ("数据库操作失败");
		} else if ($result_insert == 1) {
			$message = ("SUCCESS");
		} else {
			$message = ("支付失败");
		}
	} else {
		$message = ('签名不正确！');
	}
} else {
	$message = ("交易失败");
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>支付结果</title>
    <style type="text/css">
        *,html,body{ background: #fff;font-size: 14px;font-family: "Microsoft Yahei", "微软雅黑"}
        html,body{ width: 100%;margin: 0;padding: 0;}
        table .tips{ background: #F0F0FF;height: 35px;line-height: 35px;padding-left: 5px;font-weight: 600;}
    </style>
</head>
<body>
	<table width="98%" border="1" cellspacing="0" cellpadding="3" bordercolordark="#fff" bordercolorlight="#d3d3d3" style="margin: 10px auto;">
		<tr>
			<td colspan="2" class="tips">处理结果</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">订单编号</td>
			<td style="padding-left: 10px;">
				<label id="lborderid"><?php echo $trxMerchantOrderno; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">处理结果</td>
			<td style="padding-left: 10px;">
				<label id="lborderid"><?php echo $message; ?></label>
			</td>
		</tr>

		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="button" value="关闭"/>
			</td>
		</tr>
	</table>
</body>
</html>
