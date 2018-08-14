package com.zsagepay.test.payment;

import java.util.HashMap;
import java.util.Map;

import org.junit.Test;

import com.zsagepay.test.utils.HttpUtilKeyVal;
import com.zsagepay.test.utils.MD5Encrypt;
import com.zsagepay.test.utils.StringUtil;


public class TestPaym {
	String merchantCode = "1000000001";
	String md5Key = "123456ADSEF";
	String intoCardName = "";
	String intoCardNo = "";
	String bankCode = ""; // "102100099996";// "102";//
	String bankName = "";
	String intoCardType = "2"; // 1-对公 2-对私
	String remark = "测试出款";
	String type = "04"; // 03-非实时付款到银行卡;04-实时付款到银行卡
	
	@Test
	public void TestPayToCard() throws Exception {
		String nonceStr = StringUtil.getRandomNum(32);
		String outOrderId = StringUtil.getRandomNum(32);
		Long totalAmount = 1l;
		String sign = "";
		String url = "";
		url = "payment/payment.do";
		String signsrc = String.format(
				"bankCode=%s&bankName=%s&intoCardName=%s&intoCardNo=%s"
				+ "&intoCardType=%s&merchantCode=%s&nonceStr=%s&outOrderId=%s&totalAmount=%s&type=%s&KEY=%s",
				bankCode,bankName,intoCardName,intoCardNo,intoCardType,merchantCode,nonceStr,outOrderId,totalAmount,type,md5Key);
	    sign = MD5Encrypt.getMessageDigest(signsrc);
		Map<String, String> map=new HashMap<String, String>();
		map.put("bankCode",bankCode );
		map.put("bankName", bankName);
		map.put("intoCardName",intoCardName );
		map.put("intoCardNo", intoCardNo);
		map.put("intoCardType",intoCardType );
		map.put("merchantCode",merchantCode );
		map.put("nonceStr", nonceStr);
		map.put("outOrderId",outOrderId);
		map.put("totalAmount",totalAmount+"");
		map.put("type",type);
		map.put("remark",remark);
		map.put("sign",sign);
		map.put("notifyUrl", "http://www.baidu.com");
		/*map.put("", );*/
		String domain="";
		String ret = HttpUtilKeyVal.doPost(domain + url, map);	
		System.out.println("付款同步应答："+ret);
	}
}
