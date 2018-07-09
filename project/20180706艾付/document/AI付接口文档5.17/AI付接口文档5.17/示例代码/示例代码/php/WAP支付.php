<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>WAP支付</title>
<style>
	input[type="text"]{width: 250px;}
	input[type="submit"]{width: 80px;}
</style>
</head>
<body>
<?php
	$version = "v1";									//接口版本
	$merchant_no = "144710001674";						//商户号
	$order_no = time();									//商户订单号
	$goods_name = "充值";								//商品名称
	$order_amount = "1";								//订单金额
	$backend_url = "";									//支付结果异步通知地址
	$frontend_url = "";									//支付结果同步通知地址
	$reserve = "";										//商户保留信息
	$pay_mode = "12";									//支付模式
	$bank_code = "QQWAP";								//银行编号：WECHATWAP、QQWAP
	$card_type = "0";									//允许支付的银行卡类型
	$goods_name = base64_encode($goods_name);			//Base64编码
	$key = "8359aaa5-ad06-11e7-9f73-71f4466";		//商户接口秘钥

    //MD5签名
    $src = "version=" . $version . "&merchant_no=" . $merchant_no . "&order_no="
            . $order_no . "&goods_name=" . $goods_name . "&order_amount=" . $order_amount
            . "&backend_url=" . $backend_url . "&frontend_url="
            . $frontend_url . "&reserve=" . $reserve
            . "&pay_mode=" . $pay_mode . "&bank_code=" . $bank_code . "&card_type="
            . $card_type;
    $src .= "&key=" . $key;
    $sign = md5($src);

    //接口地址
	$url = "https://pay.all-inpay.com/gateway/pay.jsp";
?>
<center>
<b>WAP支付</b>

<form action="<?=$url?>" method="POST" id="form" >
	<table cellpadding="3">
		<tr><td>接口版本：</td><td><input type="text" name="version" value="<?=$version?>" /></td></tr>
		<tr><td>商户号：</td><td><input type="text" name="merchant_no" value="<?=$merchant_no?>" /></td></tr>
		<tr><td>商户订单号：</td><td><input type="text" name="order_no" value="<?=$order_no?>" /></tr>
		<tr><td>商品名称：</td><td><input type="text" name="goods_name" value="<?=$goods_name?>" /></tr>
		<tr><td>订单金额：</td><td><input type="text" name="order_amount" value="<?=$order_amount?>" /></tr>
		<tr><td>异步通知地址：</td><td><input type="text" name="backend_url" value="<?=$backend_url?>" /></tr>
		<tr><td>同步通知地址：</td><td><input type="text" name="frontend_url" value="<?=$frontend_url?>" /></tr>
		<tr><td>保留信息：</td><td><input type="text" name="reserve" value="<?=$reserve?>" /></tr>
		<tr><td>支付模式：</td><td><input type="text" name="pay_mode" value="<?=$pay_mode?>" /></tr>
		<tr><td>银行编号：</td><td><input type="text" name="bank_code" value="<?=$bank_code?>" /></tr>
		<tr><td>银行卡类型：</td><td><input type="text" name="card_type" value="<?=$card_type?>" /></tr>
		<tr><td>MD5签名：</td><td><input type="text" name="sign" value="<?=$sign?>" /></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="提交"></td></tr>
	</table>
</form>
</center>
</body>
</html>