package com.zspay.SDK.servlet;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServlet;

import com.zspay.SDK.util.HttpUtilKeyVal;
import com.zspay.SDK.util.MD5Encrypt;


@SuppressWarnings("serial")
public class TestQuery extends HttpServlet {

	public static void main(String[] args) throws Exception {
		Map<String, String> params = new HashMap<String, String>();
		params.put("merchantCode","1000000001");
		params.put("outOrderId", "aRT7JpC4YJWBvEYHqM3yIYwn");// 商户系统订单号
		String signsrc = String.format("merchantCode=%s&outOrderId=%s&KEY=%s",
				"1000000001","aRT7JpC4YJWBvEYHqM3yIYwn","123456ADSEF");
		System.out.println(signsrc);
		String sign = MD5Encrypt.getMessageDigest(signsrc);
		params.put("sign", sign);
		System.out.println("params:"+params.toString());
		String ssString=HttpUtilKeyVal.doPost("", params,null);
		System.out.println(ssString);
	}
}
