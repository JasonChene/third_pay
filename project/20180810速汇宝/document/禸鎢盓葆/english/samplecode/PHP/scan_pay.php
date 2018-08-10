<?php
	include ('phpqrcode.php');
	
	include_once("./merchant.php");
	
	$merchant_code = $_POST["merchant_code"];

	$service_type = $_POST["service_type"];

	$notify_url = $_POST["notify_url"];		

	$interface_version =$_POST["interface_version"];
	
	$client_ip = $_POST["client_ip"];
	
	$sign_type = $_POST["sign_type"];

	$order_no = $_POST["order_no"];

	$order_time = $_POST["order_time"];

	$order_amount =$_POST["order_amount"];

	$product_name =$_POST["product_name"];

	$product_code = $_POST["product_code"];
	
	$product_num = $_POST["product_num"];
		
	$product_desc = $_POST["product_desc"];

	$extra_return_param =$_POST["extra_return_param"];
	
	$extend_param = $_POST["extend_param"];
	

	$signStr = "";
	
	$signStr = $signStr."client_ip=".$client_ip."&";	
	
	if($extend_param != ""){
		$signStr = $signStr."extend_param=".$extend_param."&";
	}
	
	if($extra_return_param != ""){
		$signStr = $signStr."extra_return_param=".$extra_return_param."&";
	}
	
	$signStr = $signStr."interface_version=".$interface_version."&";	
	
	$signStr = $signStr."merchant_code=".$merchant_code."&";	
	
	$signStr = $signStr."notify_url=".$notify_url."&";		
	
	$signStr = $signStr."order_amount=".$order_amount."&";		
	
	$signStr = $signStr."order_no=".$order_no."&";		
	
	$signStr = $signStr."order_time=".$order_time."&";	

	if($product_code != ""){
		$signStr = $signStr."product_code=".$product_code."&";
	}	
	
	if($product_desc != ""){
		$signStr = $signStr."product_desc=".$product_desc."&";
	}
	
	$signStr = $signStr."product_name=".$product_name."&";

	if($product_num != ""){
		$signStr = $signStr."product_num=".$product_num."&";
	}	
	
	$signStr = $signStr."service_type=".$service_type;
	


	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
		
	openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	
	$sign = base64_encode($sign_info);
	

	$postdata=array('extend_param'=>$extend_param,
	'extra_return_param'=>$extra_return_param,
	'product_code'=>$product_code,
	'product_desc'=>$product_desc,
	'product_num'=>$product_num,
	'merchant_code'=>$merchant_code,
	'service_type'=>$service_type,
	'notify_url'=>$notify_url,
	'interface_version'=>$interface_version,
	'sign_type'=>$sign_type,
	'order_no'=>$order_no,
	'client_ip'=>$client_ip,
	'sign'=>$sign,
	'order_time'=>$order_time,
	'order_amount'=>$order_amount,
	'product_name'=>$product_name);
		
	echo("<script>console.log('".json_encode($postdata)."');</script>"); 

	$ch = curl_init();	
	curl_setopt($ch,CURLOPT_URL,"https://api.xhbill.com/gateway/api/scanpay");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response=curl_exec($ch);
	curl_close($ch);
	echo $response;
	

		
		
?>
