package com.smartpay.util;

public class Configuration {

	/***xxxxxxxx平台分配的商家id*/
	//public static final String merchantid = "xxxxxxxx";
	//public static final String merchantid = "8841722451";
	public static final String merchantid = "1564413232";
	
	/***xxxxxxxxxxxxxxxxxx 从平台分配的商户私钥   */
	//public static final String key        = "xxxxxxxxxxxxxxxxxx";
	//public static final String key        = "isx8polcys1kku4ta1jy23e4h8fym8ov";
	public static final String key = "yan0dcpntlogwdzpmoleiyll2rsyr216";
	/**支付请求的url, xxx.xxx.xxx.xxx  服务器的请求地址*/
	//public static final String payURL     = "http://xxx.xxx.xxx.xxx/smartpayment/pay/gateway";
	//public static final String payURL     = "http://106.15.186.0/smartpayment/pay/gateway";
	public static final String payURL     = "http://47.75.145.199/smartpayment/pay/gateway";
	
	
	
	/**支付查询的url  xxx.xxx.xxx.xxx  服务器的请求地址*/
	//public static final String queryURL   = "http://xxx.xxx.xxx.xxx/smartpayment/pay/order/query";
	//public static final String queryURL   = "http://106.15.186.0/smartpayment/pay/order/query";
	public static final String queryURL   = "http://47.75.145.199/smartpayment/pay/order/query";
	
	/**支付回调的url, 商家服务器接受回调的地址xxxx  商家回调的url地址*/
	//public static final String notifyURL  = "http://xxxxx";
	public static final String notifyURL  = "http://localhost";
	
	/**支付宝wap支付*/
	public static final String ALIPAY_WAP_SERVICE   = "pay.alipay.wappay";
	
	
	
	/**微信wap支付**/
	public static final String WEIXIN_WAP_SERVICE   = "pay.weixin.wappay";
	
}
