<?php
	
	//获取商户私钥(merchant private key)
	require_once('merchant.php');
	
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
	
	
	$service_type = "sign_query";

	$merchant_code = "200001001888";

	$interface_version = "V3.0";

	$input_charset = "UTF-8";

	$sign_type = "RSA-S";

	$mobile = "";

	$bank_code = "";

	//银行卡类型  借记卡：0 信用卡：1
	$card_type = "0";

	$card_no = "";
	
	
	$signStr = "";
	
	$signStr = $signStr."bank_code=".$bank_code."&";	
	
	$signStr = $signStr."card_no=".$card_no."&";	
	
	$signStr = $signStr."card_type=".$card_type."&";		
	
	$signStr = $signStr."input_charset=".$input_charset."&";		
	
	$signStr = $signStr."interface_version=".$interface_version."&";		
	
	$signStr = $signStr."merchant_code=".$merchant_code."&";

	$signStr = $signStr."mobile=".$mobile."&";

	$signStr = $signStr."service_type=".$service_type;
	
	echo $mobile;
	
	openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	
	$sign = base64_encode($sign_info);
		
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>	
	<body onLoad="document.dinpayForm.submit();">
		<form name="dinpayForm" method="post" action="https://api.yuanruic.com/gateway/api/express" target="_blank">
			
			<input type="hidden" name="sign" value="<?php echo $sign?>" />
			<input type="hidden" name="merchant_code" value="<?php echo $merchant_code?>" />
			<input type="hidden" name="bank_code"     value="<?php echo $bank_code?>"/>
			<input type="hidden" name="service_type"  value="<?php echo $service_type?>"/>
			<input type="hidden" name="input_charset" value="<?php echo $input_charset?>"/>
			<input type="hidden" name="mobile"    value="<?php echo $mobile?>">
			<input type="hidden" name="interface_version" value="<?php echo $interface_version?>"/>
			<input type="hidden" name="card_no"     value="<?php echo $card_no?>"/>
			<input type="hidden" name="sign_type"     value="<?php echo $sign_type?>"/>
			<input type="hidden" name="card_type"     value="<?php echo $card_type?>"/>
			</form>
	</body>
</html>

