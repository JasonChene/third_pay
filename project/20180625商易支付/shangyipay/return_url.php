<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
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
// $strRet = substr($strRet, 0, -1) . $pay_mkey;

$mysign = strtolower(md5($signText));

if ($opstate == "0") {
	if ($sign == $mysign) {
		$result_insert = update_online_money($orderid, $ovalue);
		if ($result_insert == -1) {

			$message = "会员信息不存在，无法入账";

		} else if ($result_insert == 0) {

			$message = "会员已经入账，无需重复入账";

		} else if ($result_insert == -2) {
			$message = "数据库操作失败";
		} else if ($result_insert == 1) {
			$message = "支付成功！";

		} else {
			$message = "支付失败,请重新支付！";

		}
	} else {
		//簽名不對
		$message = "签名不正确！";

		exit;
	}
} else {
//交易不成功
	$message = "交易失败！";

	exit;
}

?>


<!-- Html顯示充值資訊 須改變訂單echo變數名稱-->
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>支付同步结果展示</title>
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
			<td style="width: 120px; text-align: right;">订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderno"><?php echo $orderid; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $ovalue; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">处理结果：</td>
			<td style="padding-left: 10px;">
				<label id="lbmessage"><?php echo $message; ?></label>
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
