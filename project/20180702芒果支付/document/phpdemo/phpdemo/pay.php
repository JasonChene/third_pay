<?php
error_reporting(E_ERROR | E_PARSE);
require_once('rsa.class.php');
require_once('base.class.php');
require_once('config.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$rsa = new Rsa();
	$base = new Base();
	$data = $_POST;
	if(!isset($data['sms_code'])){
		//生成订单号
		$data['down_sn'] = $base->createNo();
		//生成签名
		$data['sign'] = $base->makeInSign($data, $conf['member_secret']);
		//组合数据
		$post = [
			'member_code' => $conf['member_code'],
			'cipher_data' => $rsa->encrypt($data, $conf['public_path']),
		];
		//提交到接口
		$url = $conf['url'].'/api/trans/pay';
		$res = $base->curlPost($url, $post);
		echo json_encode($res);
		exit;
	}else{
		//生成签名
		$data['sign'] = $base->makeInSign($data, $conf['member_secret']);
		//组合数据
		$post = [
			'member_code' => $conf['member_code'],
			'cipher_data' => $rsa->encrypt($data, $conf['public_path']),
		];
		//提交到接口
		$url = $conf['url'].'/api/trans/verify';
		$res = $base->curlPost($url, $post);
		echo json_encode($res);
		exit;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>php-sdk-支付</title>
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
				<select name="type_code" id="typeCode">
					<?php
					foreach ($conf['type_codes'] as $k => $v) {
					?>
					<option value="<?=$k?>"><?=$v?></option>
					<?php
					}
					?>
				</select>
			</td>
		</tr>
		<!--
		<tr>
			<td>收银台</td>
			<td>
				<select name="netbank" id="netbank">
					<option value="">不使用</option>
					<?php
					foreach ($conf['netbank'] as $k => $v) {
					?>
					<option value="<?=$k?>"><?=$v?></option>
					<?php
					}
					?>
				</select>
			</td>
		</tr>
		-->
		<tr>
			<td>主题</td>
			<td><input type="text" name="subject" value="测试" /></td>
		</tr>
		<tr>
			<td>交易金额</td>
			<td><input type="text" name="amount" value="0.1" /></td>
		</tr>
		<tr>
			<td>银行卡类型</td>
			<td>
				<select name="card_type">
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
			<td>渠道</td>
			<td>
				<select name="agent_type">
					<option value="1">PC端</option>
					<option value="2">手机端</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>手机号码</td>
			<td><input type="text" name="mobile" value="" /></td>
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
			<td>notify url</td>
			<td><input type="text" name="notify_url" value="http://您的域名/paynotify.php" /></td>
		</tr>
		<tr>
			<td>return url</td>
			<td><input type="text" name="return_url" value="" /></td>
		</tr>
		<tr>
			<td>客户端ip</td>
			<td><input type="text" name="client_ip" value="<?=$_SERVER['REMOTE_ADDR'] ?>" /></td>
		</tr>
		<tr>
			<td>支付授权码</td>
			<td><input type="text" name="auth_code" value="" /></td>
		</tr>
		<tr>
			<td></td><td><input type="button" value="提交" id="ajaxPost"></td>
		</tr>
	</table>
</form>

<p>= 短信验证无磁无密 ===========================</p>
<form method="post" id="smsForm">
	<table>
		<tr>
			<td>订单号</td>
			<td><input type="text" name="order_sn" /></td>
		</tr>
		<tr>
			<td>手机号码</td>
			<td><input type="text" name="mobile" value="" /></td>
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
			<td>短信验证码</td>
			<td><input type="text" name="sms_code" /></td>
		</tr>
		<tr>
			<td></td><td><input type="button" value="ajax提交" id="smsPost"></td>
		</tr>
	</table>
</form>
<div class="i-qrcode" id="qrcode"></div>
<script type="text/javascript">
$(document).on("click", "#ajaxPost", function() {
	var type_code = $('#typeCode').val();
	$('#qrcode').html('');
    $.ajax({
	    type: 'POST',
	    url: 'pay.php',
	    dataType: 'json',
	    data: $('#ajaxForm').serialize(),
	    success: function(res){
	    	console.log(res, type_code);
	    	if(res.code == '0000'){
	    		if(type_code == 'gateway' || type_code == 'wxh5'){
	    			//网关形式
	    			window.open(res.code_url);
	    		}else if(type_code == 'sms'){
	    			$('#smsForm input[name="order_sn"]').val(res.order_sn);
	    		}else{
	    			var qrcode = new QRCode(document.getElementById("qrcode"), {
					    text: res.code_url,
					    width: 200,
					    height: 200,
					    colorDark : "#000000",
					    colorLight : "#ffffff",
					    correctLevel : QRCode.CorrectLevel.H
					});
	    		}
	    	}else{
	    		alert(res.code+':'+res.msg);
	    	}
	    }
	});
});
$(document).on("click", "#smsPost", function() {
	$.ajax({
	    type: 'POST',
	    url: 'pay.php',
	    dataType: 'json',
	    data: $('#smsForm').serialize(),
	    success: function(res){
	    	console.log(res);
	    	alert(res.code+':'+res.msg);
	    }
	});
});
</script>
</body>
</html>