package com.yipin.pay;

import com.alibaba.fastjson.JSONObject;
import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;
import org.apache.commons.codec.digest.DigestUtils;

import java.util.Map;

public class PayQueryDemo {
    private static String orderNumber = "25f49cac99a0413883e62557b6f608d3";
    private static String signature = generateCipherTextQuery();

    /**
     * 测试订单查询接口
     */
    public static void main(String[] args) {
        OkHttpClient okHttpClient = new OkHttpClient();
        FormBody fromBody = new FormBody.Builder()
                .add("appId", PayDemo.appId)
                .add("orderNumber", orderNumber)
                .add("signature", signature)
                .build();
        Request request = new Request.Builder()
                .url(PayDemo.host+"/pay/business/querys")
                .post(fromBody)
                .build();
        Response response;
        try {
            response = okHttpClient.newCall(request).execute();
            if (response.body() != null) {
                String responseBody = response.body().string();
                System.out.println(responseBody);
                Map map = JSONObject.parseObject(responseBody, Map.class);
                //校验
                String success = map.get("success").toString();
                String orderNumber = map.get("orderNumber").toString();
                String signature = map.get("signature").toString();
                String key = PayDemo.key;
                String express = success + "&" + orderNumber + "&" + key;
                String cipherTextReturn = DigestUtils.md5Hex(express).toUpperCase();
                if (cipherTextReturn.equals(signature)) {
                    System.out.println("校验成功！");
                    if (success.equals("true")) {
                        /*
                        处理商户系统的业务逻辑
                        */
                        System.out.println("交易成功，用户已成功支付！");
                    }
                } else {
                    System.out.println("校验失败！");
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    //生成签名
    private static String generateCipherTextQuery() {
        System.out.println(PayQueryDemo.orderNumber);
        String express = PayDemo.appId + "&" + PayQueryDemo.orderNumber + "&" + PayDemo.key;
        System.out.println(express);
        return DigestUtils.md5Hex(express).toUpperCase();
    }
}
