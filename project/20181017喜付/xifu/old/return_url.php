<? header("content-Type: text/html; charset=UTF-8"); ?>
<?php
include_once("../config.php");
include_once("../../../database/mysql.config.php");
include_once("../moneyfunc.php");
include_once("./fun.php");


$body = trim($_REQUEST['body']);
$buyer_email = trim($_REQUEST['buyer_email']);
$buyer_id = trim($_REQUEST['buyer_id']);
$discount = trim($_REQUEST['discount']);
$ext_param1 = trim($_REQUEST['ext_param1']);
$ext_param2 = trim($_REQUEST['ext_param2']);
$gmt_create = trim($_REQUEST['gmt_create']);
$gmt_logistics_modify = trim($_REQUEST['gmt_logistics_modify']);
$gmt_payment = trim($_REQUEST['gmt_payment']);
$is_success = trim($_REQUEST['is_success']);
$is_total_fee_adjust = trim($_REQUEST['is_total_fee_adjust']);
$notify_id = trim($_REQUEST['notify_id']);
$notify_time = trim($_REQUEST['notify_time']);
$notify_type = trim($_REQUEST['notify_type']);
$order_no = trim($_REQUEST['order_no']);
$payment_type = trim($_REQUEST['payment_type']);
$price = trim($_REQUEST['price']);
$quantity = trim($_REQUEST['quantity']);
$seller_actions = trim($_REQUEST['seller_actions']);
$seller_email = trim($_REQUEST['seller_email']);
$seller_id = trim($_REQUEST['seller_id']);
$title = trim($_REQUEST['title']);
$total_fee = trim($_REQUEST['total_fee']);
$trade_no = trim($_REQUEST['trade_no']);
$trade_status = trim($_REQUEST['trade_status']);
$use_coupon = trim($_REQUEST['use_coupon']);

$sign = trim($_REQUEST['sign']);
$signType = trim($_REQUEST['signType']);


$parms = array();
foreach ($_REQUEST as $key => $value) {
	if ($key == "sign") {
		continue;
	} else if ($value == "signType") {
		continue;
	} else if ($value === "") {
		continue;
	} else {
		$parms[$key] = $value;
	}
}


$params = array(':m_order' => $order_no);
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

ksort($parms);
$strRet = "";
foreach ($parms as $key => $value) {
	if ($value === "") {
		continue;
	}
	if ($key == "sign") {
		continue;
	}
	if ($key == "signType") {
		continue;
	}
	$strRet .= "$key=" . $value . "&";
}
$strRet = substr($strRet, 0, -1) . $pay_mkey;
//logData("待签名串" . $strRet);
$mysign = strtoupper(sha1($strRet));



$signText = 'body=' . $body . '&buyer_email=' . $buyer_email . '&buyer_id=' . $buyer_id . '&discount=' . $discount . '&ext_param1=' . $ext_param1 . '&ext_param2=' . $ext_param2 . '&gmt_creat=' . $gmt_creat . '&gmt_logistics_modify=' . $gmt_logistics_modify . '&gmt_payment=' . $gmt_payment . '&is_success=' . $is_success . '&is_total_fee_adjust=' . $is_total_fee_adjust . '&notify_id=' . $notify_id . '&notify_time=' . $notify_time . '&notify_type=' . $notify_type . '&order_no=' . $order_no . '&payment_type=' . $payment_type . '&price=' . $price . '&quantity=' . $quantity . '&seller_actions=' . $seller_actions . '&seller_email=' . $seller_email . '&seller_id=' . $seller_id . '&title=' . $title . '&total_fee=' . $total_fee . '&trade_no=' . $trade_no . '&trade_status=' . $trade_status . '&use_coupon=' . $use_coupon;
$mysign = strtoupper(sha1($signText));

if ($sign == $mysign) {
	if ($is_success == "T") {
		$result_insert = update_online_money($sdorderno, $total_fee);
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
		$message = '交易失败！';
	}
} else {
	$message = '签名不正确！';
}
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>创富同步结果展示</title>
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
			<td style="width: 120px; text-align: right;">商户订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderid"><?php echo $order_no; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">创富订单号：</td>
			<td style="padding-left: 10px;">
				<label id="lborderno"><?php echo $trade_no; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $total_fee; ?></label>
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
