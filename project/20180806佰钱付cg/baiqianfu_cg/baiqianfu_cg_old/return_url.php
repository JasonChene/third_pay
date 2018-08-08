<?php
include_once("../../../database/mysql.config.php");//原数据库的连接方式
include_once("../moneyfunc.php");

$MerNo = trim($_REQUEST['MerNo']);// 商戶號
$Amount = trim($_REQUEST['Amount']);// 訂單金額
$BillNo = trim($_REQUEST['BillNo']);// 訂單號
$Succeed = trim($_REQUEST['Succeed']);// 狀態碼

$MD5info = trim($_REQUEST['MD5info']);// Return参数签名
$Result = trim($_REQUEST['Result']);// 支付状态说明
$MerRemark = trim($_REQUEST['MerRemark']);// 商户自定义备注信息

$params = array(':m_order' => $BillNo);
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

$signtext = '';
$signtext .= "Amount=" . $Amount . "&";
$signtext .= "BillNo=" . $BillNo . "&";
$signtext .= "MerNo=" . $MerNo . "&";
$signtext .= "Succeed=" . $Succeed . "&";
$md5key = mb_strtoupper(md5($pay_mkey));
$signtext .= $md5key;
$md5sign = mb_strtoupper(md5($signtext));

//if(notify回傳成功)
if ($MD5info == $md5sign) {
	$mymoney = number_format($Amount, 2, '.', '');
	$result_insert = update_online_money($BillNo, $mymoney);
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
	$message = '签名不正确！';
}

?>
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
				<label id="lborderno"><?php echo $BillNo; ?></label>
			</td>
		</tr>
		<tr>
			<td style="width: 120px; text-align: right;">充值金额：</td>
			<td style="padding-left: 10px;">
				<label id="lbpayamount"><?php echo $mymoney; ?></label>
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