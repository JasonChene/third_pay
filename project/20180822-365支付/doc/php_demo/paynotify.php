<?php
header("Content-Type: text/html;charset=utf-8");
include 'base.php';
/**
 * ---------------------通知异步回调接收页-------------------------------
 * 
 * 此页就是您之前传给PayApi的notify_url页的网址
 * 支付成功，PayApi会根据您之前传入的网址，回调此页URL，post回参数
 * 启用安全证书，需要服务器开启OpenSSL服务
 * --------------------------------------------------------------
 */
//echo "回调";
$file = "notify_ok";
$path ="/data/wwwroot/"; //输出到可写入的目录
$content = "ok";
F($file,$content,$path);

if($_SERVER['REQUEST_METHOD'] == "POST"){ 
	
	$json = file_get_contents("php://input");
	$json_arr = json_decode(stripslashes($json),true); 

	$content = $json_arr;
	F($file,$content,$path);
	
	$paysapi_id = $json_arr["paysapi_id"]; 	//服务器API接口返回的唯一支付编码ID	
	$order_id = $json_arr["order_id"];			//用户订单编号ID
    $is_type = $json_arr["is_type"];				//支付类型
	$price = $json_arr["price"];				//订单金额
    $real_price = $json_arr["real_price"];		//实际支付金额   
	$mark = $json_arr["mark"];					//此处填写产品名称，或充值，消费说明	
    $code = $json_arr["code"];					//订单状态
	$sign = $json_arr["sign"];					//服务器API接口返回的唯一key
	
    //请检查传入的参数是否格式正确
	$api_code = "34393032";								//此处填写商户的id;
    $api_key = "d57a2b05447e305a0f67ff0e5627cc86"; 	//此处填写的密钥
	
	$signdata = array( 		
		'api_code' => $api_code,			//商户的id;	
		'paysapi_id' => $paysapi_id,		//服务器API接口返回的唯一支付编码ID	
		'order_id' => $order_id,			//用户订单编号ID
		'is_type' => $is_type,				//支付类型						
		'price' => $price,					//订单金额
		'real_price' => $real_price,		//实际支付金额   							
		'mark' => $mark,					//此处填写产品名称，或充值，消费说明
		'code' => $code						//订单状态
	);  	
	
	$content = "POST"." ".$paysapi_id." ".$order_id." ".$is_type." ".$price." ".$real_price." ".$mark." ".$code." ".$sign;
	F($file,$content,$path);

    $temp_sign = make_sign($signdata,$api_key);
	
    if ($temp_sign != $sign){

		$content = "sign"." ".$temp_sign." ".$sign;
		F($file,$content,$path);
		echo "sign_ERROR";
    }else{
		
        //校验key成功，执行自己的业务逻辑：加余额，订单付款成功，装备购买成功等等。
		$data = array(
			'paysapi_id' => $paysapi_id,		//服务器API接口返回的唯一支付编码ID	
			'order_id' => $order_id,			//用户订单编号ID
			'is_type' => $is_type,				//支付类型						
			'price' => $price,					//订单金额
			'real_price' => $real_price,		//实际支付金额   							
			'mark' => $mark,					//此处填写产品名称，或充值，消费说明
			'code' => $code						//订单状态
		);		

		$content = "sign_SUCCESS"." ".$temp_sign." ".$sign;
		F($file,$content,$path);
				 
		echo "SUCCESS";		 

    }

}

?>