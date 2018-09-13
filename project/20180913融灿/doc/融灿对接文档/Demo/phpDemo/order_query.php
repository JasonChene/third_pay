<? header("content-Type: text/html; charset=UTF-8");?>
<?php

	/*
		*The private key Java uses the pkcs8 format, the 1024 bit private key, and if the PHP needs to be used, it is split into Base64 format*
	*/
	$private = 'MIICXAIBAAKBgQDDJtax805/fazA0L9Aluo3APLKYwhXo5GQXHcqeZD6hriCN0CVyh/U1evFM/uwN2hXib0ye8VU+P9vi98atxaRKqhwZAk3w5B3+xgeAIcycpgXV5CQu8GC74WBjX+fZr1Kp5uklNF5LTmW+orSr5yyMrqUBSagyhOeB3izAUMWKwIDAQABAoGAKG2+YqU+KOlXRaa5SF87bGO06Lq1erp3KIoPYexHXa9VQBzEM4PpLkWXD4aiaLJB2oi9elzr15uyB2DZuoYdlV4dWTLaymle2wOxSwZikjVd/WPzCBs4CzMUveprdiZ8Q9i0Vg1dSss0JZgTUn+ZYo4bkHLbZvmuwQFezUpzSQkCQQD7EZ9/hjHEb2PFbf1XJXUvz1Q7YgJJk6ICJbisAkJ6wIgK/jxv7S0WOTy054fw4AJmWCQsjNOnNlLTvzoEBFftAkEAxvwQTLAtWEXIfqq8fSeT9/sl/THHl5ERr1wbolrKTZgQ5mlaAhjYI3dHDd14Dl2dYt4K4uE6kklECN6pcWIzdwJAHn2SaDNM4fVBIZ3s8WNsKuU77oZFjtfuCeK43bLcjfnJy8P6ZQFhw1wqIjIoETmzMOitY/eqUJlJT8veaNR6NQJAPkXYXAeuyTddnbaVMp3WQv9ITkrr6dUusbCjvVWohkwWzeUelBVuXvsPM5mh3u0ROkW/Jm3SRMHFFvmIEcHn1QJBAPrknIioCH7mCK8rQ5gx9iBaQRyJGQisICuLURLKSr3zYauoayflU3HPLpjmNF3UrowbGpL50B2FYyMfjshErtA=';
	$merchantCode = 'M0000001';
	$orderNo = time();
	$signType = 'RSA';
	$signData = 'merchantCode='+$merchantCode+'&orderNo='+$orderNo;
	$pi_key = openssl_pkey_get_private($private);
	openssl_sign($signData, $sign, $pi_key, OPENSSL_ALGO_SHA1);
	$sign = base64_encode($sign);
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body onLoad="javascript:document.getElementById('queryForm').submit();">
		<form  id="queryForm" action="https://api.rcpays.com/gateway/query" method="post"  target="_self">
			<input type="hidden" name="merchantCode" value="<?php echo $merchantCode?>" />
			<input type="hidden" name="orderNo" value="<?php echo $orderNo?>" />
			<input type="hidden" name="signType" value="<?php echo $signType?>" />
			<input type="hidden" name="sign" value="<?php echo $sign?>" />
		</form>
	</body>
</html>
