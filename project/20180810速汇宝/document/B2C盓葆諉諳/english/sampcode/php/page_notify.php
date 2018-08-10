<?php

	include_once("./merchant.php");
	
	$merchant_code	= $_POST["merchant_code"];	

	$interface_version = $_POST["interface_version"];

	$sign_type = $_POST["sign_type"];

	$dinpaySign = base64_decode($_POST["sign"]);

	$notify_type = $_POST["notify_type"];

	$notify_id = $_POST["notify_id"];

	$order_no = $_POST["order_no"];

	$order_time = $_POST["order_time"];	

	$order_amount = $_POST["order_amount"];

	$trade_status = $_POST["trade_status"];

	$trade_time = $_POST["trade_time"];

	$trade_no = $_POST["trade_no"];

	$bank_seq_no = $_POST["bank_seq_no"];

	$extra_return_param = $_POST["extra_return_param"];

			
	$signStr = "";
	
	if($bank_seq_no != ""){
		$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
	}

	if($extra_return_param != ""){
		$signStr = $signStr."extra_return_param=".$extra_return_param."&";
	}	

	$signStr = $signStr."interface_version=".$interface_version."&";	

	$signStr = $signStr."merchant_code=".$merchant_code."&";

	$signStr = $signStr."notify_id=".$notify_id."&";

	$signStr = $signStr."notify_type=".$notify_type."&";

    $signStr = $signStr."order_amount=".$order_amount."&";	

    $signStr = $signStr."order_no=".$order_no."&";	

    $signStr = $signStr."order_time=".$order_time."&";	

    $signStr = $signStr."trade_no=".$trade_no."&";	

    $signStr = $signStr."trade_status=".$trade_status."&";

	$signStr = $signStr."trade_time=".$trade_time;
	
	
	
    $dinpay_public_key = openssl_get_publickey($dinpay_public_key);
	
	$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);
	
	$result="";
	
	if($flag==true){		
		
		$result="deposit successful";
		
	}else{
		
		$result="deposit failed";
	}
	

	
?>
<!DOCTYPE HTML>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<title>Dinpay</title>
	</head>
		<body>
				<?php echo $result?>
		</body>
</html>