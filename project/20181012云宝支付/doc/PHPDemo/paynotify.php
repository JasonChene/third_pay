<?php
/**
 * ---------------------通知异步回调接收页-------------------------------
 * 
 * 此页就是您之前传给的notify_url页的网址
 * 支付成功，平台会根据您之前传入的网址，回调此页URL，post回参数
 * 
 * --------------------------------------------------------------
 */
	$code = $_POST['code'];				// 0000为支付成功
	$order_id = $_POST['order_id'];		//传入的订单号
	$order_uid = $_POST['order_uid'];	//传入的order_uid
	$price = $_POST['price'];			//支付金额
	$transaction_id = $_POST['transaction_id'];			//渠道流水号
	$token = "xxxxxxxxx";//商户密钥
	
    if($code=='0000'){
			//验证是否是安全
			$key = md5($order_id.$order_uid.$price.$transaction_id.$token);
			$rekey = $_POST['key'];
			if($key == $rekey){
				//此处处理您自己的业务
            	exit('success');
			}
			else exit('fail');
        }else{
            exit('fail');
   }


  

?>