<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>支付</title>
<meta name="keywords" content="" />
<link href="css/yeepaytest.css" type="text/css" rel="stylesheet" />
</head>
<?php
include 'Config.php';
include 'Sign.php';

$outOrderId = $_POST['outOrderId'];
$totalAmount = $_POST['totalAmount'];
// 蛋蛋的忧伤 转成东八区时间
$orderCreateTime = date("YmdHis", time() + 3600 * 8);
// 设置定的最晚支付时间为当前时间后延一天
$lastPayTime = date("YmdHis", strtotime("+1 days") + 3600 * 8);
?>
<body onLoad="document.zlinepay.submit();" class="center">
	<form type="hidden" name='zlinepay' action='<?php echo($payUrl) ?>'
		method='post' />
	<input type='hidden' name='merchantCode'
		value='<?php echo($merchantCode) ?>' />
	<input type='hidden' name='outOrderId'
		value='<?php echo($outOrderId) ?>' />
	<input type='hidden' name='totalAmount'
		value='<?php echo($totalAmount) ?>' />
	<input type='hidden' name='goodsName'
		value='<?php echo($_POST['goodsName']) ?>' />
	<input type='hidden' name='goodsExplain'
		value='<?php echo($_POST['goodsExplain']) ?>' />
	<input type='hidden' name='orderCreateTime'
		value='<?php echo($orderCreateTime) ?>' />
	<input type='hidden' name='lastPayTime'
		value='<?php echo($lastPayTime) ?>' />
	<input type='hidden' name='ext' value='<?php echo($_POST['ext']) ?>' />
	<input type='hidden' name='merUrl'
		value='<?php echo($_POST['merUrl']) ?>' />
	<input type='hidden' name='noticeUrl'
		value='<?php echo($_POST['noticeUrl']) ?>' />
	<input type='hidden' name='bankCode'
		value='<?php echo($_POST['bankCode']) ?>' />
	<input type='hidden' name='bankCardType'
		value='<?php echo($_POST['bankCardType']) ?>' />
		<?php
$s = new Sign();
// 参与签名字段
$sign_fields = Array(
    "merchantCode",
    "outOrderId",
    "totalAmount",
    "orderCreateTime",
    "lastPayTime"
);
$map = Array(
    "merchantCode" => $merchantCode,
    "outOrderId" => $outOrderId,
    "totalAmount" => $totalAmount,
    "orderCreateTime" => $orderCreateTime,
    "lastPayTime" => $lastPayTime
);
$sign = $s->sign_mac($sign_fields, $map, $md5Key);
// 将小写字母转成大写字母
$sign = strtoupper($sign);
?>
		<input type='hidden' name='sign' value='<?php echo($sign) ?>' />
	</form>
</body>
</html>