package com;

import java.io.IOException;
import java.util.Map;
import java.util.TreeMap;

/**
 * Created by wsk on 2018-05-03.
 */
public class CallbackPay {
    public static void main(String[] args) throws IOException {
        String url="";//回调地址
        Map<String, String> reqMap = new TreeMap<String, String>();
        reqMap.put("mid", "");// 商户号
        reqMap.put("orderNo","");// 商户订单号：
        reqMap.put("amount", "");// 订单金额
        reqMap.put("type", "");// 支付种类：
        reqMap.put("code", "1");// 返回码：
        reqMap.put("msg", "支付成功");// 返回消息：0:支付中1：支付成功2：支付失败 3：支付关闭
        String result = HttpUtil.post(url, reqMap);
        if (result.equals("success")) {
            System.out.println("回调成功");
        }else {
            System.out.println("回调失败");
        }
    }
}
