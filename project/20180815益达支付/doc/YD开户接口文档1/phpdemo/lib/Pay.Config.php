<?php
 class PayConfig{
 	

	//平台公钥
	const PUBLIC_KEY = '输入平台公钥';

    //商户私钥
	const MCH_PRIVATE_KEY = '输入商户私钥';

    //支付统一下单地址
	const PAY_URL = "http://主域名/pay/unify";
	
	//订单查询地址
	const QUERY_URL = "http://主域名/pay/orderinfo";
	
	//代付订单提交地址
	const WITHDRAW_URL = "http://主域名/pay/withdrawApply";
	
	//代付订单查询地址
	const WITHDRAWSTATUS_URL = "http://主域名/pay/withdrawStatus";

	//日志路径
	const LOG_PATH = "logs";
	
	
	
 }
  
