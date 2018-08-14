package com.zsagepay.test.payment;

import java.util.HashMap;
import java.util.Map;

import org.junit.Test;

import com.zsagepay.test.utils.HttpUtilKeyVal;
import com.zsagepay.test.utils.MD5Encrypt;
import com.zsagepay.test.utils.StringUtil;

public class TestState {
	
	@Test
	public void testState() throws Exception {
		String nonceStr = StringUtil.getRandomNum(32);
		String outOrderId = "WTFK0000000788";
		String md5Key = "123456ADSEF";
		String merchantCode="1000000001";
		String url = "payment/queryState.do";
		String domain="";
		String sign = "";
		String signsrc = String.format(
				"merchantCode=%s&nonceStr=%s&outOrderId=%s&KEY=%s",
				merchantCode,nonceStr,outOrderId,md5Key);
	    sign = MD5Encrypt.getMessageDigest(signsrc);
	    Map<String, String> map=new HashMap<String, String>();
		map.put("merchantCode",merchantCode );
		map.put("nonceStr", nonceStr);
		map.put("outOrderId", outOrderId);
		map.put("sign", sign);
		String ret = HttpUtilKeyVal.doPost(domain + url, map);	
		System.out.println("查询出款状态  同步应答："+ret);
		
	}

}
