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


if ($trade_status == "TRADE_SUCCESS") {
	if ($mysign == $sign) {
		$mymoney = number_format($total_fee, 2, '.', ''); //订单金额
		$result_insert = update_online_money($order_no, $mymoney);
		if ($result_insert == -1) {
			$message = ("会员信息不存在，无法入账");
			exit;
		} else if ($result_insert == 0) {
			$message = ("success");
			exit;
		} else if ($result_insert == -2) {
			$message = ("数据库操作失败");
			exit;
		} else if ($result_insert == 1) {
			$message = ("success");
			exit;
		} else {
			$message = ("支付失败");
			exit;
		}
	} else {
		$message = ('签名不正确！');
		exit;
	}
} else {
	$message = ("交易失败");
	exit;
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
			<td style="width: 120px; text-align: right;">订单号</td>
			<td style="padding-left: 10px;">
				<label id="lborderid"><?php echo $order_no; ?></label>
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