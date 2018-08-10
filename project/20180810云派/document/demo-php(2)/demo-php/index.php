<?php
	

include_once 'CloudPay.php';

//在CloudPay.php 设置 MERCHANT_NO 和 KEY 值。

#统一下单
$arr_data['trade_amount'] = 1000; //金额，单位分
$arr_data['subject'] = '商品名称'; 
$arr_data['pay_type'] = 'LS_BANK'; //开通的渠道
$arr_data['settlement_type'] = 'T0';     //结算方式
$arr_data['mobile'] = '18850503800';     //付款卡手机号
$arr_data['spbill_create_ip'] = '127.0.0.1';      //发起支付人的ip
$arr_data['notify_url'] = 'http://www.xxx.com/callback';  //回调地址
$arr_data['out_trade_no'] = date('ymdHis',time()) . rand(10000,99999);    //唯一订单号
$cloudpay = new CloudPay();
$data = $cloudpay->orders($arr_data);

var_dump($data);

header("location:".$data['data']);

?>