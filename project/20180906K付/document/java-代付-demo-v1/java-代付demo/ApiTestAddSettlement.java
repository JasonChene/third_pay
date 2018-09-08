package com.wensheng.pay.gateway;

import org.junit.Test;

import com.alibaba.fastjson.JSONObject;
import com.wensheng.pay.trade.utils.gateway.PayUtils;

import junit.framework.TestCase;

/**
 * Unit test for simple App.
 */
public class ApiTestAddSettlement extends TestCase{
    @Test
    public static void queryTest(){
    	String merAccount = ""; // 商户标识
        String merKey = ""; // 商户标识
        long time = System.currentTimeMillis()/1000; // 时间戳
        JSONObject json = new JSONObject();
        json.put("merAccount", merAccount);
        json.put("time", time);
        json.put("realName", "张三");
        json.put("accountType", "1");
        json.put("bankNo", "6xxxxxxxxxxxx");
        json.put("province", "福建省");
        json.put("city", "福州市");
        json.put("subBankName", "招商银行鼓楼支行");
        json.put("subBankNo", "135656565");
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
