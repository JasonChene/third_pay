package com.nowtopay;



public class pay_config {
	
	
	// 商户ID (官方提供)
	public static String partner="16962";
	
	// 商户的私钥 (官方提供)
	public static String key="a7307538dab143fcaa7edb741a31629d";
	
    //官方api地址（固定下面地址）
	public static String  apiurl="https://gateway.nowtopay.com/nowtopay.html";
	
    //商户异步通知地址
	public static String  	notify_url="http://xxxx/notify_url.jsp";
	
    //支付完成,商户跳转地址
	public static String  return_url="http://xxxx/xxx.jsp" ;

}
