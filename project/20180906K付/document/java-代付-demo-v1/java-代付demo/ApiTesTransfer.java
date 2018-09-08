package com.wensheng.pay.gateway;

import org.junit.Test;

import com.alibaba.fastjson.JSONObject;
import com.wensheng.pay.trade.utils.gateway.PayUtils;

import junit.framework.TestCase;

/**
 * Unit test for simple App.
 */
public class ApiTesTransfer extends TestCase{
    @Test
    public static void queryTest(){
    	String merAccount = ""; // 商户标识
        String merKey = ""; // 商户标识
        long time = System.currentTimeMillis()/1000; // 时间戳
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("time", time);
        json.put("settlementId", "9dca86dcbb3f4782b9ac0f4447be8292");
        json.put("amount", "800");
        json.put("orderNo", "test_order_4");
        json.put("notifyUrl", "http://xxxxx/xxx/xx");
        String sign = PayUtils.buildSign(json,merKey);
        json.put("sign", sign);
        String data = PayUtils.buildData(json,merKey);
        JSONObject result = PayUtils.httpGet("", merAccount, data);
        System.out.println(result.toJSONString());
    }
    public static void main(String[] args) {
    	queryTest();
	}
}
