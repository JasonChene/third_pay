package com.zsagepay.test.payment;

import java.util.HashMap;
import java.util.Map;

import org.junit.Test;

import com.zsagepay.test.utils.HttpUtilKeyVal;
import com.zsagepay.test.utils.MD5Encrypt;
import com.zsagepay.test.utils.StringUtil;


public class TestQueryMerBalance {
	
	@Test
	public void merBalance() throws Exception {
		
		String nonceStr = StringUtil.getRandomNum(32);
		String md5Key = "123456ADSEF";
		String merchantCode="1000000001";
		String url = "payment/merBalance.do";
		String domain="";
		String sign = "";
		String signsrc = String.format(
				"merchantCode=%s&nonceStr=%s&KEY=%s",
				merchantCode,nonceStr,md5Key);
	    sign = MD5Encrypt.getMessageDigest(signsrc);
	    Map<String, String> map=new HashMap<String, String>();
		map.put("merchantCode",merchantCode );
		map.put("nonceStr", nonceStr);
		map.put("sign", sign);
		String ret = HttpUtilKeyVal.doPost(domain + url, map);	
		System.out.println("查询商户余额 同步应答："+ret);
	}
	

}
