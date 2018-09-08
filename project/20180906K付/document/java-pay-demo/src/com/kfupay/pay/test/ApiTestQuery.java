package com.kfu.pay.test;

import com.alibaba.fastjson.JSONObject;
import com.mbpay.pay.utils.PayUtils;

/**
 * Unit test for simple App.
 */
public class ApiTestOrderForQuery {
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
    public static void testQuery(){
    	String merAccount = ""; // 商户标识
        String merNo = ""; // 商户编号
        String merKey = ""; // 商户密钥
        String mbOrderId = "ORde12312312312"; // 商户订单号
        long time = System.currentTimeMillis()/1000; // 时间戳 "
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("mbOrderId", mbOrderId);
        json.put("time", time);
        String sign = PayUtils.buildSign(json,merKey);
        json.put("sign", sign);
        String data = PayUtils.buildData(json,merKey);
        JSONObject result = PayUtils.httpGet("", merAccount, data);
//        JSONObject result = PayUtils.httpGet("", merAccount, data);  正式地址
        System.out.println(result.toJSONString());
    }
    
    public static void main(String[] args) {
    	testQuery();
	}
}

