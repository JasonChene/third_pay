package com.zspay.netbank.demo.servlet;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServlet;

import com.zspay.netbank.demo.utils.HttpUtilKeyVal;
import com.zspay.netbank.demo.utils.MD5Encrypt;
@SuppressWarnings("serial")
public class TestQuery extends HttpServlet {

	public static void main(String[] args) throws Exception {
		Map<String, String> params = new HashMap<String, String>();
		params.put("merchantCode","1000000001");
		params.put("outOrderId", "v32CIOqKFMUiYQ1LUSuFI4Cu");// 商户系统订单号
		//签名
		String signsrc = String.format("merchantCode=%s&outOrderId=%s&KEY=%s",
				"1000000001","v32CIOqKFMUiYQ1LUSuFI4Cu","123456ADSEF");
		System.out.println("signsrc:"+signsrc);
		String sign = MD5Encrypt.getMessageDigest(signsrc);
		params.put("sign", sign);
		System.out.println("params:"+params.toString());
		String ssString=HttpUtilKeyVal.doPost("", params,null);
		System.out.println(ssString);
	}
}
