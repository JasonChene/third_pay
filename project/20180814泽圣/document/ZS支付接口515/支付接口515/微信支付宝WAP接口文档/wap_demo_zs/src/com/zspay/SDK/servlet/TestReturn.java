package com.zspay.SDK.servlet;

import java.util.HashMap;
import java.util.Map;
import org.apache.log4j.Logger;
import org.junit.Test;

import com.zspay.SDK.util.HttpUtilKeyVal;
import com.zspay.SDK.util.MD5Encrypt;
public class TestReturn {
	//正式地址
	private String url="";
	private Logger log = Logger.getLogger(TestReturn.class);
	@Test
	public void returnTest() throws Exception {
		String payOrderId = "1004000000000100017";//支付系统订单号
		Long returnAmt = 1l;//退款金额
		String merchantCode="1000000001";//测试商户号
		String outOrderId = "b5284d72-894a-4e87-865a-c09sacd106809";//退货商户订单号
		returnTest(merchantCode, payOrderId, returnAmt,outOrderId);
	}

	private void returnTest(String merchantCode, String payOrderId, Long returnAmount,String outOrderId) throws Exception {
		Map<String, String> param = new HashMap<String, String>();
		param.put("merchantCode", merchantCode);
		param.put("orderId", payOrderId);
		param.put("outOrderId", outOrderId);
		param.put("amount", returnAmount.toString());
		param.put("remark", "测试退款备注");
		param.put("applicant", "测试退款申请人");
		String signsrc = String.format("amount=%s&merchantCode=%s&orderId=%s&outOrderId=%s&KEY=%s", returnAmount,merchantCode, payOrderId, outOrderId,
				"123456ADSEF");
		String sign = MD5Encrypt.getMessageDigest(signsrc);
		param.put("sign", sign);
		String ret = HttpUtilKeyVal.doPost(url, param, null);
		log.info("ebank 创建订单 应答报文:" + ret);
	}
}
