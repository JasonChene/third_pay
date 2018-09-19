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
    $key = $_POST['key'];			//生成订单时候的key，原样返回
	$sign = $_POST['sign'];			//验签sign

    $token = '';             //分配商户的token
    $my_sign = md5($order_id.$price.$token);          //拼接顺序：order_id+price+token 后md5加密

    if($code=='0000' && $sign==$my_sign){
			//此处处理您自己的业务
            exit('success');
        }else{
            exit('fail');
   }


?>