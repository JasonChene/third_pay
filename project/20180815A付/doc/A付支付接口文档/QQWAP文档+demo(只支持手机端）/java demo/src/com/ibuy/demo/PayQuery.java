package com.ibuy.demo;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.ibuy.utils.MerchantApiUtil;
import com.ibuy.utils.PayConfigUtil;
import com.ibuy.utils.SimpleHttpUtils;

import java.util.HashMap;
import java.util.Map;

/**
 * <b>功能说明:支付订单查询</b>
 * @author 
 */
public class PayQuery {

    public static void main(String[] args){
        Map<String , Object> paramMap = new HashMap<String , Object>();

        paramMap.put("payKey", PayConfigUtil.readConfig("payKey")); // 商户支付Key
        paramMap.put("outTradeNo", "116310172172");//原交易订单号

        /////签名及生成请求API的方法///
        String sign = MerchantApiUtil.getSign(paramMap, PayConfigUtil.readConfig("paySecret"));
        paramMap.put("sign", sign);

        String payResult = SimpleHttpUtils.httpPost(PayConfigUtil.readConfig("payQueryUrl"), paramMap);

        System.out.println(payResult);

        JSONObject jsonObject = JSON.parseObject(payResult);
        Object resultCode = jsonObject.get("resultCode");//返回码
        Object errMsg = jsonObject.get("errMsg");//错误信息(请求失败时)

        if ("0000".equals(resultCode.toString())){//请求成功
            System.out.println("请求成功");
        }else{//请求失败
            System.out.println(errMsg);
        }

    }
}
