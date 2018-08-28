<?php 
//引用文件
require(dirname(__FILE__) . '/init.php');

//银联支付
if($_REQUEST['act'] =='yl')
{
	$order['transAmt'] = 1;    //金额，单位为分
	$order['orderNo'] = 100000010; //订单号

	//发起支付请求
	$payUrl = UnionPay($order);
}else if($_REQUEST['act'] =='sm'){//扫码支付
	$order['transAmt'] = 300;    //金额，单位为分
	$order['orderNo'] = 100000012; //订单号

	//发起支付请求
	$payUrl = ScavengingPay($order);
}else if($_REQUEST['act'] =='cx'){//订单查询
	// $order['transAmt'] = 1;    //金额，单位为分
	$order['transDate'] = '20180528';    //原订单交易日期 yyyyMMdd
	$order['orderNo'] = 100000010; //订单号

	//发起订单查询请求
	$order = QueryOrder($order);
}else if($_REQUEST['act'] =='df'){//代付
	$order['transAmt'] = 1;    //金额，单位为分
	$order['orderNo'] = 100000014; //订单号

	//发起代付请求
	$order = Substitute($order);
}else if($_REQUEST['act'] =='back'){  //测试回调
	$result = '{"orderNo":"20180528105101","actualAmount":"993","transAmt":"1000","transDate":"20180528","orderStatus":"1","ret_msg":"交易成功","tranSerno":"20180528105102","ret_code":"0000"}';
	$back = back($result);
}else{
	echo "银联支付：act=yl<br>";
	echo "扫码支付：act=sm<br>";
	echo "订单查询：act=cx<br>";
	echo "代付支付：act=df<br>";
	echo "测试回调：act=back<br>";
	echo "值是写死的，请手动更改";
}

?>