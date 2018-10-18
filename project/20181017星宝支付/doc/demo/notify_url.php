<?php

require_once("epay.config.php");

//收款sdk
$sdk=$_REQUEST['sdk'];
//支付金额
$money=$_REQUEST['money'];
//平台交易号
$trade_no=$_REQUEST['order'];
//商户订单号
$out_trade_no=$_REQUEST['record'];
//签名
$sign=$_REQUEST['sign'];

//生成签名
$sign_index = md5(floatval($money) . trim($out_trade_no) . $sdk);
if($sign_index == $sign) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //判断该笔订单是否在商户网站中已经做过处理
    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
    //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
    //如果有做过处理，不执行商户的业务程序

    //注意：
    //付款完成后，支付宝系统发送该交易状态通知

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
	
}
else {
    //验证失败
    echo "fail";
}
?>