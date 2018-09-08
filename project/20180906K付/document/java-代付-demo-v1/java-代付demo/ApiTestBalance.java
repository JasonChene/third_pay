package com.wensheng.pay.gateway;

import org.junit.Test;

import com.alibaba.fastjson.JSONObject;
import com.wensheng.pay.trade.utils.gateway.PayUtils;

import junit.framework.TestCase;

/**
 * Unit test for simple App.
 */
public class ApiTestBalance extends TestCase{
    @Test
    public static void queryTest(){
    	String merAccount = ""; // 商户标识
        String merKey = ""; // 商户标识
        long time = System.currentTimeMillis()/1000; // 时间戳
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("time", time);
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
