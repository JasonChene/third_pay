package com.ibuy.demo;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.ibuy.utils.AESUtils;
import com.ibuy.utils.MerchantApiUtil;
import com.ibuy.utils.PayConfigUtil;
import com.ibuy.utils.SimpleHttpUtils;

/**
 */
public class QuickPay {

	public static void main(String[] args) {
		
		pay(String.valueOf(System.currentTimeMillis()));//H5网关快捷
		
	}
	
	/**
	 * @param outTradeNo
	 */
	public static void pay(String outTradeNo){
		Map<String, Object> paramMap = new HashMap<String, Object>();
		paramMap.put("productType", "40000701");
		paramMap.put("payKey", PayConfigUtil.readConfig("payKey"));// 商户支付Key
		paramMap.put("orderPrice", "10");
		
		paramMap.put("payBankAccountNo","");//支付银行卡
		
		/**新增参数**/
		paramMap.put("payPhoneNo","");//手机号码
		paramMap.put("payBankAccountName","");//开户人姓名
		paramMap.put("payCertNo","");//身份证号码
		
		paramMap.put("outTradeNo", outTradeNo);
		paramMap.put("productName", "纸巾");// 商品名称
		paramMap.put("orderIp", PayConfigUtil.readConfig("orderIp"));// 下单IP
		paramMap.put("orderTime", new SimpleDateFormat("yyyyMMddHHmmss").format(new Date()));// 订单时间
		paramMap.put("returnUrl", PayConfigUtil.readConfig("returnUrl"));// 页面通知返回url
		paramMap.put("notifyUrl", PayConfigUtil.readConfig("notifyUrl")); // 后台消息通知Url
		paramMap.put("subPayKey", PayConfigUtil.readConfig("subPayKey"));
		paramMap.put("remark", "支付备注");


		paramMap.put("sign", MerchantApiUtil.getSign(paramMap, PayConfigUtil.readConfig("paySecret")));
		//System.out.println("请求报文Map:" + paramMap);
		String payResult = SimpleHttpUtils.httpPost(PayConfigUtil.readConfig("quickPayGateWay"), paramMap);
		System.out.println(payResult);
		
	}
	
}
