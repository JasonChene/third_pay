<?php
	include_once("config.php");

	$dingdan    = $_GET['dingdan']; /* 商户订单号*/       		 
	
	//获取订单状态值
	$zt = get_dingdan($dingdan,2);
	//支付完成
	if($zt == 1){
		echo '<br><br><h3>恭喜您，支付完成，订单号：'.$dingdan.'</h3>';
	}else{
		echo 'no';
	}
?>