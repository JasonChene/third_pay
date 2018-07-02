<?php
error_reporting(E_ERROR | E_PARSE);
require_once('rsa.class.php');
require_once('base.class.php');
require_once('config.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$rsa = new Rsa();
	$base = new Base();
	$data = $_POST;
	//生成签名
	$data['sign'] = $base->makeInSign($data, $conf['member_secret']);
	//组合数据
	$post = [
		'member_code' => $conf['member_code'],
		'cipher_data' => $rsa->encrypt($data, $conf['public_path']),
	];
	//提交到接口
	$url = $conf['url'].'/api/settle/pay';
	$res = $base->curlPost($url, $post);
	echo json_encode($res);
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>php-sdk-代付申请</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<body class="">
<script src="jquery.js"></script>
<script src="qrcode.min.js"></script>
<form method="post" id="ajaxForm">
	<table>
		<tr>
			<td>支付类型</td>
			<td>
				<select name="type_code">
					<option value="t0apidf">在途API代付</option>
					<option value="t1apidf">余额API代付</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>交易金额</td>
			<td><input type="text" name="amount" value="0.1" /></td>
		</tr>
		<tr>
			<td>银行卡类型</td>
			<td>
				<select name="account_type">
					<option value="1">对私借记卡</option>
					<option value="2">对私贷记卡</option>
					<option value="3">对公借记卡</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>银行代号</td>
			<td><input type="text" name="bank_segment" value="103" /></td>
		</tr>
		<tr>
			<td>用户类型</td>
			<td>
				<select name="user_type">
					<option value="1">个人</option>
					<option value="2">企业</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>手机</td>
			<td><input type="text" name="account_phone" value="" /></td>
		</tr>
		<tr>
			<td>姓名</td>
			<td><input type="text" name="account_name" value="" /></td>
		</tr>
		<tr>
			<td>证件号码</td>
			<td><input type="text" name="id_card_no" value="" /></td>
		</tr>
		<tr>
			<td>银行卡号</td>
			<td><input type="text" name="account_no" value="" /></td>
		</tr>
		<tr>
			<td>联行号</td>
			<td><input type="text" name="bank_no" value="" /></td>
		</tr>
		<tr>
			<td>银行所在城市</td>
			<td><input type="text" name="city_name" value="" /></td>
		</tr>
		<tr>
			<td></td><td><input type="button" value="提交" id="ajaxPost"></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
$(document).on("click", "#ajaxPost", function() {
	$('#qrcode').html('');
    $.ajax({
	    type: 'POST',
	    url: 'settle.php',
	    dataType: 'json',
	    data: $('#ajaxForm').serialize(),
	    success: function(res){
	    	console.log(res);
	    	alert(res.code+':'+res.msg);
	    }
	});
});
</script>
</body>
</html>