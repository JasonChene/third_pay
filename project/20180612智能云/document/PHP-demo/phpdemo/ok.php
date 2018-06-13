<?php

	$ordno = $_REQUEST["ordno"];
	//status 1 支付成功，通知成功 2 支付成功，通知中 3 待支付 4 支付失败，已超时
	//注意本状态返回值只做参考，请以异步通知为准；
	$status = $_REQUEST["status"];
	$orderid = $_REQUEST["orderid"];


	if (isset($status) and $status < 3) {
		echo "<a href='index.html'>订单支付成功 </a>";
	} else {
		echo "<a href='index.html'>订单支付失败 </a>";
	}

?>
