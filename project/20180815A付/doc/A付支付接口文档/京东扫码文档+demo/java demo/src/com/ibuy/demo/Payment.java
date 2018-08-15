package com.ibuy.demo;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.ibuy.utils.MD5Util;
import com.ibuy.utils.MerchantApiUtil;
import com.ibuy.utils.PayConfigUtil;
import com.ibuy.utils.SimpleHttpUtils;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import org.apache.commons.httpclient.util.DateUtil;

/**
 * 功能说明: 无卡支付
 * @author 
 */
public class Payment {
	
	public static void main(String[] args) {

		 Map<String, Object> paramMap = new HashMap<String, Object>();
		 paramMap.put("payKey", PayConfigUtil.readConfig("payKey"));	//商户支付Key
		 
		 paramMap.put("orderPrice", "0.01");							//金额
		 
		 String orderNo = String.valueOf(System.currentTimeMillis()); 	//订单编号
		 paramMap.put("outTradeNo", orderNo);
		 
		  paramMap.put("productType", "80000202");// JD钱包D0
//		 paramMap.put("productType", "80000203");// JD钱包T0
//		 paramMap.put("productType", "80000201");// JD钱包T1

		Date orderTime = new Date();// 订单时间
		String orderTimeStr = new SimpleDateFormat("yyyyMMddHHmmss").format(orderTime).trim();// 订单时间
		paramMap.put("orderTime", orderTimeStr);
		paramMap.put("productName", "test product");// 商品名称
		String orderIp = PayConfigUtil.readConfig("orderIp").trim(); // 下单IP
		paramMap.put("orderIp", orderIp);
		String returnUrl = PayConfigUtil.readConfig("returnUrl").trim(); // 页面通知返回url
		paramMap.put("returnUrl", returnUrl);
		String notifyUrl = PayConfigUtil.readConfig("notifyUrl").trim(); // 后台消息通知Url
		paramMap.put("notifyUrl", notifyUrl);
		
		
		paramMap.put("subPayKey", PayConfigUtil.readConfig("subPayKey").trim());
		paramMap.put("remark", "remark");

		///// 签名及生成请求API的方法///
		String sign = MerchantApiUtil.getSign(paramMap, PayConfigUtil.readConfig("paySecret").trim());
		paramMap.put("sign", sign);
		
		System.out.println("测试地址：" + PayConfigUtil.readConfig("paymentUrl").trim());
		System.out.println("请求参数：" + paramMap);
		
		System.out.println("开始请求:" + DateUtil.formatDate(new Date(), "yyyy-MMdd HH:mm:ss SSS"));
		String payResult = SimpleHttpUtils.httpPost(PayConfigUtil.readConfig("paymentUrl").trim(), paramMap);
		System.out.println("结束请求:" + DateUtil.formatDate(new Date(), "yyyy-MMdd HH:mm:ss SSS"));

		System.out.println("响应结果：" + payResult);
		JSONObject jsonObject = JSON.parseObject(payResult);
		Object resultCode = jsonObject.get("resultCode");// 返回码
		Object payMessage = jsonObject.get("payMessage");// 请求结果(请求成功时)
		Object errMsg = jsonObject.get("errMsg");// 错误信息(请求失败时)

		if ("0000".equals(resultCode.toString())) {// 请求成功
			System.out.println("付款信息：\n" + payMessage);
		} else {// 请求失败
			System.out.println("错误信息：" + errMsg);
		}
	}
		

}
