<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<title></title>
<style type="text/css">
.bank {
	display: inline-block;
}

.input-out {
	padding-top: 20px;
}

.span-out {
	border-bottom: 1px solid #ddd;
	padding: 15px 5px;
}

select,input[type=number],input[type=text] {
	font-size: 16px;
	width: 100%;
	height: 30px;
	border-left: none;
	border-right: none;
	border-top: none;
}
</style>
</head>
<body onLoad="document.zlinepay.submit();">
<?php
include 'Config.php';
include 'Sign.php';

$random = "";
for($i = 0; $i < 32; $i ++) {
	$random .= rand ( 0, 9 );
}
$outOrderId = $_POST['outOrderId'];
$merchantOrderTime = $_POST['merchantOrderTime'];
$goodsName = $_POST['goodsName'];
$goodsDescription = $_POST['goodsDescription'];
$totalAmount = $_POST['totalAmount'];
$ext = $_POST['ext'];

// 设置定的最晚支付时间为当前时间后延一天
// date("YmdHis", strtotime("+1 days")+3600 * 8 )
$lastPayTime = "";
?>

<form action="<?php echo ($orderUrl) ?>" name="zlinepay"	method="post">
<input type="hidden" class="form-control" placeholder="商户号" 	name="merchantCode" value="<?php echo($merchantCode) ?>" /> 
<input type="hidden" class="form-control" placeholder="商户用户ID" name="outUserId" value="" /> 
<input type="hidden" class="form-control" placeholder="商户订单号"	name=outOrderId value="<?php echo ($outOrderId)?>" />
<input type="hidden" class="form-control" placeholder="支付金额(单位:分)"  name="totalAmount" value="<?php echo $totalAmount ?>" /> 
<input type="hidden" class="form-control" placeholder="商品名称" name="goodsName" value="<?php echo $goodsName ?>" /> 
<input type="hidden" class="form-control" placeholder="商品描述" name="goodsDescription" value="<?php echo $goodsDescription ?>">
<input type="hidden" class="form-control" placeholder="后台通知地址" name="notifyUrl" value="<?php echo $notifyUrl ?>"> 
<input type="hidden" class="form-control" placeholder="商户订单时间" name="merchantOrderTime" value="<?php echo ($merchantOrderTime)?>" /> 
<input type="hidden" class="form-control" placeholder="最晚支付时间"	name="latestPayTime" value="<?php echo ($lastPayTime)?>" /> 
<input type="hidden" class="form-control" placeholder="扩展字段"	name="ext" value="<?php echo ($ext) ?>" />
<input type="hidden" class="form-control" placeholder="商户取货地址"	name="merUrl" value="<?php echo $merUrl ?>" />
<input type="hidden" class="form-control" placeholder="随机字符串" name="randomStr" value="<?php echo ($random)?>" /> 
<?php 
	$s = new Sign();
	// 参与签名字段
	$sign_fields = Array("merchantCode", "outOrderId", "totalAmount", "merchantOrderTime", "notifyUrl", "outUserId");
	$map = Array("merchantCode"=>$merchantCode, "outOrderId"=>$outOrderId, "totalAmount"=>$totalAmount, "merchantOrderTime"=>$merchantOrderTime, "notifyUrl"=>$notifyUrl, "outUserId"=>$outUserId);
	$sign = $s->sign_mac($sign_fields, $map, $md5Key);
	// 将小写字母转成大写字母
	$sign = strtoupper($sign);
?>
<input type="hidden" class="form-control" placeholder="签名字符串" name="sign" value="<?php echo($sign) ?>" /> 
</form>
</body>
</html>

