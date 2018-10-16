package com.cn.hunst.demo;

import java.util.HashMap;
import java.util.Map;

import com.cn.hunst.util.HttpUtil;
import com.cn.hunst.util.Signature;

public class QueryDemo {
	
	
	public static void main(String[] args) {
		Map<String, String> paramMap = new HashMap<String, String>();
		paramMap.put("merchant_no", "10000000019");// 商户号
		paramMap.put("order_no", "1528685861780"); //支付金额
		paramMap.put("sign", Signature.sign(paramMap, "abc"));
	//	String result = HttpUtil.methodPost("http://47.105.46.192:7071/pay/cnp/gateway", paramMap, "utf-8");
		String result = HttpUtil.methodPost("http://localhost:8080/pay/query", paramMap, "utf-8");
		System.out.println("返回的参数是：" + result);
		//{"complete_time":"","ord_amount":1.10,"ord_status":"WAITING_PAYMENT","order_no":"","payment_trx_no":"PAY1005992208991518720","resp_code":"0000","resp_msg":"查询成功","sign":"08a7b0f7d1941b2ba8f58ffbb56239d3"}
	}
}
