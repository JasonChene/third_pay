<?php

$data = !empty($_POST)?$_POST:$_GET;

//验证签名 
$apikey = "ASD51FD84A3DF4541FF5S8A652S157S8";
$md5_sign = strtoupper(md5("pay_memberid=".$data['pay_memberid']."&out_trade_id=".$data['out_trade_id']."&orderstatus=".$data['orderstatus']."&paymoney=".$data['paymoney'].$apikey));

if($data['sign'] != $md5_sign){
	echo "验签失败";die;
}

//判断订单状态
if($data['orderstatus'] == 1){
	//订单支付成功，添加数据处理
	
	
	
	echo "ok";
}else{
	
}
