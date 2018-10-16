package com.mustpay.config;

/* *
 *类名：MustpayConfig
 *功能：基础配置类
 *详细：设置帐户有关信息及返回路径
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 *该代码仅供学习和研究MustPay接口使用，只是提供一个参考。
 */

public class MustpayConfig {
	
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

	//MustPay平台公钥
	public static String PLATE_PUBLIC_KEY = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDO7CQpYHhEonv1g9YjRVGJDaCOu0bXogD7pBLQu2dDvJ8TGROCEw6ArIWgAWEEE1uEShPBa4MpCP4ZMjT5RMj45o0pb0Z8s4k6CpS9D1LFK9msNpsN8PyaRDQC86R6jxAVQMWgfIZ9cxfZR8Ple3GJGjwBfeRnzh75rE1DHCBOcwIDAQAB";

	//商户私钥
	public static String MER_PRIVATE_KEY = "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKCB7DD1Vbb4zVh5gUIQv0JbPiiTQHcZKVPzfbXxS76IDB2y6pTOq2PbXiURrag7a0FVLsdlJX+zIX8d93KNeLn+Fa8Cb0oJeZQzciZYDdFEui3HtwizY3DoP2mnrk4faEzMXUGlMUrBV7WBMopxwspup0x+rtgn5h20lOUYg0ybAgMBAAECgYAVwgb2jAtWhluvxqjS/9otcJj4fx2aB3smujcsVs1hwqeBzyMlkO6C1tXoSIE18PgVHyr8NKXkra+4v6MvkCXxOZvY4wVNL8RjaMksjtZNbBRaddIe2psqklFDH8do2BoxfvBdnkWulz9A//k2U08N4c2kJ5AUCe6nE5UjNFJcgQJBAM8wBJcDV1sEGUShaKWuTIt/JdPPJdYbw/SPCSDQRBvjjaQqu2nr8B6ONDLXZqey1yS7LJF1hFdzDC4kCXCYNpECQQDGUoSH1CPeP3IGFPAui20kJNPKgm9Zecf+/VTa78WPtOF5DwHVSHD4X+qt2Hrp1dLCgGmLltFHNV+b/SslR55rAkBXCthSzT+M6ErpT1pUiMZ1sIQm2RcPPXj0rIbsNzL1+IKQHre/xzSI0btSRLZG69aBAvW1Yoan6piKZe9lUz1RAkA7IuPt9K31WYnQknHED0MuIeUdX6OAVLX0LOoelpyca11IUddEF+PHzCIYUJLmIyJDaTMPspsY1qt5whYZea+dAkAJFamFbLtnag7ol1Q8LO4r44nf3OnpOV61T/pOyxLAj9lKtx/vizyihM5/2OcOK0mpt9YgbDmDx0MVSUbvn8RT";
	
	//测试商户apps_id
	public static String APPS_ID = "5d0006abd0414412b6d994cbd7dcc85d";
	
	//测试商户mer_id
	public static String MER_ID = "17072512021831085";
	
	//异步回调URL 此地址必须外网可访问
	public static String NOTIFY_URL = "http://xxx/testpay/MustPay-JAVA-UTF-8/notify_url.jsp";
	
	//同步回调URL 此地址必须外网可访问
	public static String RETURN_URL = "http://xxx/testpay/MustPay-JAVA-UTF-8/return_url.jsp";
	
	// 签名方式
	public static String SIGN_TYPE = "RSA";
		
	// 字符编码格式 目前支持utf-8
	public static String INPUT_CHARSET = "utf-8";
	
	//下单地址
	public static String ADD_ORDER_URL = "https://service.chinaxiangqiu.com/service/order/saveOrder";
	
	//订单查询地址
	public static String QUERY_ORDER_URL = "https://service.chinaxiangqiu.com/service/order/queryOrder";


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

}

