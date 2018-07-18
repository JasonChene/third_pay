<?php
header("Content-type: text/html; charset=utf-8");

require("helper.php");

$merchantCode = $_POST["merchant_code"];
$orderNo = $_POST["order_no"];
$orderTime = $_POST["order_time"];
$orderAmount = $_POST["order_amount"];
$trade_status = $_POST["trade_status"];
$tradeNo = $_POST["trade_no"];
$returnParams = $_POST["return_params"];
$trade_time = $_POST['trade_time'];
$notify_type = $_POST['notify_type'];
$sign= $_POST["sign"];


$kvs = new KeyValues();

$kvs->add("merchant_code", $merchantCode);
$kvs->add("notify_type", $notify_type);
$kvs->add("order_no", $orderNo);
$kvs->add("order_time", $orderTime);
$kvs->add("order_amount", $orderAmount);
$kvs->add("trade_status", $trade_status);
$kvs->add("trade_no", $tradeNo);
$kvs->add("trade_time", $trade_time);
$kvs->add("return_params", $returnParams);
$_sign = $kvs->sign();

//商户系统返回HTTP状态码为200，服务器则认为通知成功，如果不为200则为失败。失败后，会陆续发送五次通知。
if ($_sign == $sign)
{
	if ($trade_status == "success")
	{
		$tradeResult = "success";
	}
	else
	{
		//失败,通知支付平台失败
		header('HTTP/1.1 404 Not Found');
		header("status: 404 Not Found");
		exit;
	}
}
else
{
	//失败,通知支付平台失败（状态码只要不是200就行）
	header('HTTP/1.1 404 Not Found');
	header("status: 404 Not Found");
	exit;
}

$orderNo = $_POST["order_no"];
$orderAmount = $_POST["order_amount"];
$orderTime = $_POST["order_time"];

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
<title></title>
</head>
<body>
<div style="text-align: center; padding-top: 20px;">
<h1>支付结果</h1>
</div>
<div style="text-align: center; font-size: 20px; color: red;"><strong><?=$tradeResult?></strong>
</div>
<div>
<table align="center" cellpadding="2" style="margin-top: 5px;">
	<tr>
		<td>订单号：</td>
		<td><?=$orderNo?></td>
	</tr>
	<tr>
		<td>订单金额：</td>
		<td><?=$orderAmount?></td>
	</tr>
	<tr>
		<td>订单时间：</td>
		<td><?=$orderTime?></td>
	</tr>
</table>
</div>
</body>
</html>
