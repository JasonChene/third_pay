<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>获取网关银行列表</title>
<style>
	input[type="text"]{width: 250px;}
	input[type="submit"]{width: 80px;}
</style>
</head>
<body>
<?php
	$merchant_no = "144710001674";						//商户号
	$key = "8359aaa5-ad06-11e7-9f73-71f4466";	//商户接口秘钥
	$mode = "WEBPAY";									//模式

	//MD5签名
	$md5Src = "merchant_no=" . $merchant_no . "&mode=" . $mode . "&key=".  $key;
	$sign = md5($md5Src);

	//接口地址
	$url = "https://pay.all-inpay.com/gateway/queryBankList";
?>
<center>
<b>获取网关银行列表</b>
<form action="<?=$url?>" method="get" id="form" >
	<table cellpadding="3">
		<tr><td>商户号：</td><td><input type="text" name="merchant_no" value="<?=$merchant_no?>" /></td></tr>
		<tr><td>模式：</td><td><input type="text" name="mode" value="<?=$mode?>" /></td></tr>
		<tr><td>MD5签名：</td><td><input type="text" name="sign" value="<?=$sign?>" /></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="提交"></td></tr>
	</table>
</form>
</center>
</body>
</html>