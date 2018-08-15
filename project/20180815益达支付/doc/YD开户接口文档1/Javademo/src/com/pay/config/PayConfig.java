package com.pay.config;

/* *
 *类名：payConfig
 *功能：基础配置类
 *详细：设置帐户有关信息及返回路径
 *版本：1.1
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

public class PayConfig {
	
	//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	// 商户ID
	public static String partner = "填写测试商户号";

	// 字符编码格式 目前支持 gbk 或 utf-8
	public static String input_charset = "UTF-8";
	
	 //平台公钥，不可改
	public static String publicKey="填写平台公钥";
	
	//下游的密钥对，测试账号
	public static String cusPublicKey="测试账号公钥";
	public static String cusPrivateKey="测试商户私钥";


	// 统一支付地址
	public static final String UNIFY_PAY_URL = "http://主域名/pay/unify";
	//代付地址
	public static final String WITHDRAW_APPLY_URL = "http://主域名/pay/withdrawApply";
	//代付结果查询地址
	public static final String WITHDRAW_QUERY_URL = "http://主域名/pay/withdrawStatus";

}
