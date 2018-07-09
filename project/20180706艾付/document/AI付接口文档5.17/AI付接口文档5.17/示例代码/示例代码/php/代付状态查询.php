<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>代付状态查询</title>
<style>
	input[type="text"]{width: 250px;}
	input[type="submit"]{width: 80px;}
</style>
</head>
<body>
<?php
	$merchant_no = "144710001674";                    //商户号
	$order_no = "1516091917";                     //商户订单号
	$key = "8359aaa5-ad06-11e7-9f73-71f4466";  //商户接口秘钥

    //MD5签名
    $src = "merchant_no=" . $merchant_no . "&order_no=" . $order_no . "&key=" . $key;
    $sign = md5($src);

    //接口地址
    $url = "https://pay.all-inpay.com/withdraw/queryOrder";
?>
<center>
<b>代付状态查询</b>

<form action="<?=$url?>" method="post" id="form">
	<table cellpadding="3">
		<tr><td>商户号：</td><td><input type="text" name="merchant_no" value="<?=$merchant_no?>" /></td></tr>
		<tr><td>商户订单号：</td><td><input type="text" name="order_no" value="<?=$order_no?>" /></tr>
		<tr><td>MD5签名：</td><td><input type="text" name="sign" value="<?=$sign?>" /></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="提交"></td></tr>
	</table>
</form>
</center>
</body>
</html>