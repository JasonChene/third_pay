<?php

$payKe = $_REQUEST["payKe"];		//商户支付Key 
			$productName = $_REQUEST["productName"];		//支付产品名称
			$outTradeNo = $_REQUEST["outTradeNo"];		//商户订单号 
			$orderPrice = $_REQUEST["orderPrice"];		//订单金额
			
			$tradeStatus = $_REQUEST["tradeStatus"];	//订单状态 
			$successTime = $_REQUEST["successTime"];		//成功时间
			$orderTime = $_REQUEST["orderTime"];	//下单时间
			$trxNo = $_REQUEST["trxNo"];	//交易流水号
			
			$sign = $_REQUEST["sign"];	//交易流水号
			
			
			

if ($tradeStatus=='SUCCESS') {


echo 'SUCCESS';



}
















?>