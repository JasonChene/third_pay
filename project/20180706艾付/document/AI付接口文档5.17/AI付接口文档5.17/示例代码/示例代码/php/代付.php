<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>代付</title>
<style>
	input[type="text"]{width: 250px;}
	input[type="submit"]{width: 80px;}
</style>
</head>
<body>
<?php			
	$merchant_no = "144710001674";									//商户号
	$order_no = time();												//商户订单号
	$card_no = "1111222233334444";									//银行卡号
	$account_name = base64_encode('王大明');						//银行开户名,使用base64进行编码（UTF-8编码）
	$bank_branch = "";												//银行支行名称,对公账户需填写，使用base64进行编码（UTF-8编码）
	$cnaps_no = "";													//银行联行号,银行唯一识别编号，对公账户需填写
	$bank_code = "ICBC";											//银行代码,参考银行代码对照表
	$bank_name= base64_encode("中国工商银行");										//银行名称,参考银行代码对照表，使用base64进行编码（UTF-8编码）
	$amount="3";													//代付金额,最多两位小数
	$pay_pwd="F571C8FB03FE5720ED0B760B86779916524F3449674B";		//支付密码(需先在商戶後台配置支付密碼,獲取支付密碼)
	$key = "8359aaa5-ad06-11e7-9f73-71f4466";					//商户接口秘钥

    //MD5签名
    $src = "merchant_no=" . $merchant_no . "&order_no="
            . $order_no . "&card_no=" . $card_no . "&account_name=" . $account_name
            . "&bank_branch=" . $bank_branch . "&cnaps_no="
            . $cnaps_no . "&bank_code=" . $bank_code
            . "&bank_name=" . $bank_name . "&amount=" . $amount . "&pay_pwd="
            . $pay_pwd;
    $src .= "&key=" . $key;
    $sign = md5($src);

    //接口地址
	$url = "https://pay.all-inpay.com/withdraw/singleWithdraw";
?>
<center>
<b>查询支付订单</b>

<form action="<?=$url?>" method="POST" id="form">
	<table cellpadding="3">
		<tr><td>商户号：</td><td><input type="text" name="merchant_no" value="<?=$merchant_no?>" /></td></tr>
		<tr><td>商户订单号：</td><td><input type="text" name="order_no" value="<?=$order_no?>" /></tr>
		<tr><td>银行卡号：</td><td><input type="text" name="card_no" value="<?=$card_no?>" /></tr>
		<tr><td>银行开户名：</td><td><input type="text" name="account_name" value="<?=$account_name?>" /></tr>
		<tr><td>银行支行名称：</td><td><input type="text" name="bank_branch" value="<?=$bank_branch?>" /></tr>
		<tr><td>银行联行号：</td><td><input type="text" name="cnaps_no" value="<?=$cnaps_no?>" /></tr>
		<tr><td>银行代码：</td><td><input type="text" name="bank_code" value="<?=$bank_code?>" /></tr>
		<tr><td>银行名称：</td><td><input type="text" name="bank_name" value="<?=$bank_name?>" /></tr>
		<tr><td>代付金额：</td><td><input type="text" name="amount" value="<?=$amount?>" /></tr>
		<tr><td>MD5签名：</td><td><input type="text" name="sign" value="<?=$sign?>" /></tr>
		<tr><td colspan="2" align="center"><input type="submit" value="提交"></td></tr>
	</table>
</form>
</center>
</body>
</html>