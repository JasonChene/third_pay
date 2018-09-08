package com.kfu.pay.test;

import com.alibaba.fastjson.JSONObject;
import com.mbpay.pay.utils.PayUtils;

/**
 * Unit test for simple App.
 */
public class ApiTestOrderForOrder {
//    /**
//     * Create the test case
//     *
//     * @param testName name of the test case
//     */
//    public ApiTest( String testName )
//    {
//        super( testName );
//    }
//
//    /**
//     * @return the suite of tests being tested
//     */
//    public static Test suite()
//    {
//        return new TestSuite( ApiTest.class );
//    }
//
//    /**
//     * Rigourous Test :-)
//     */
//    public void testApp()
//    {
//        assertTrue( true );
//    }
    public static void testOrder(){
    	String merAccount = ""; // 商户标识
        String merNo = ""; // 商户编号
        String merKey = ""; // 商户密钥
        String orderId = "ORde12312312312"; // 商户订单号
        long time = System.currentTimeMillis()/1000; // 时间戳
        String amount = "5000"; //支付金额
        String productType = "1"; //商品类别码
        String product = "支付测试"; //商品名称
        String productDesc = "test测试"; // 商品描述
        String userType = "0"; // 用户类型
        String payWay = "UNIONPAY";
        String payType = "SCANPAY_UNIONPAY"; // 支付类型
        String userId = ""; // 用户标识
        String appId = ""; // 微信公众号
        String userIp = "127.0.0.1"; //用户IP地址
        String returnUrl = "http://www.baidu.com";
        String notifyUrl = "https://www.xxxxxx.com/"; 
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("merNo", merNo);
        json.put("orderId", orderId);
        json.put("time", time);
        json.put("amount", amount);
        json.put("productType", productType);
        json.put("product", product);
        json.put("productDesc", productDesc);
        json.put("userType", userType);
        json.put("payWay", payWay);
        json.put("payType", payType);
        json.put("userId", userId);
        json.put("appId", appId);
        json.put("userIp", userIp);
        json.put("returnUrl", returnUrl);
        json.put("notifyUrl", notifyUrl);
        String sign = PayUtils.buildSign(json,merKey);
        json.put("sign", sign);
        String data = PayUtils.buildData(json,merKey);
        JSONObject result = PayUtils.httpGet("", merAccount, data);
//        JSONObject result = PayUtils.httpGet("", merAccount, data);  正式地址
        System.out.println(result.toJSONString());
    }
    
    public static void main(String[] args) {
    	testOrder();
	}
}

