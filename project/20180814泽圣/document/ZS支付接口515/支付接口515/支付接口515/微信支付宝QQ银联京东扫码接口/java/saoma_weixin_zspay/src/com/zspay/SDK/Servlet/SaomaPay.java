package com.zspay.SDK.Servlet;

import java.util.Date;

import org.junit.Test;

import com.alibaba.fastjson.JSONObject;
import com.zspay.SDK.util.DateUtil;
import com.zspay.SDK.util.HttpUtilKeyVal;
import com.zspay.SDK.util.Security;
import com.zspay.SDK.util.StringUtil;

public class SaomaPay {
	public static String outOrderId = StringUtil.getRandomNum(32);// 必填
	public static String md5key = "123456ADSEF";
	public static String payUrl = "scan/entrance.do";
	public static String model = "QR_CODE";// 必填 只能填写QR_CODE
	public static String merchantCode = "1000000001";// //必填，需要填写自己的正式商户号
	public static String deviceNo = "";
	public static Long amount = 10L;// 必填，单位分
	public static String goodsName = "ceshi";
	public static String goodsExplain = "";
	public static String ext = "";
	public static String orderCreateTime = DateUtil.formatDate2(new Date());// 必填
	public static String lastPayTime = "20170617170217";
	public static String noticeUrl = "http://www.baidu.com";
	public static String goodsMark = "";
	public static int isSupportCredit = 1;// 必填，默认1-代表支持信用卡
	public static String ip = "192.168.1.1";// 必填， 这个必须填写为商户的IP,或者用户的IP
	public static String sign = "";// 必填
	public static String url = "";
	public static String payChannel = "21";//21微信，30-支付宝，

	
	public String pay() throws Exception {
		final String[] signFields = { "merchantCode", "outOrderId", "amount",
				"orderCreateTime", "noticeUrl", "isSupportCredit" };
		JSONObject json = new JSONObject();
		json.put("merchantCode", merchantCode);
		json.put("outOrderId", outOrderId);
		json.put("amount", amount);
		json.put("orderCreateTime", orderCreateTime); // 必填
		json.put("noticeUrl", noticeUrl);
		json.put("isSupportCredit", isSupportCredit);
		try {// 签名
			String sign = Security.countSignMd5(md5key, signFields, json);
			json.put("sign", sign);
			System.out.println("签名的sign:" + sign);
		} catch (Exception e) {
			System.out.println("签名失败");
		}
		json.put("goodsName", goodsName);
		json.put("goodsExplain", goodsExplain);
		json.put("ext", ext);
		json.put("model", model);
		json.put("deviceNo", deviceNo);
		json.put("lastPayTime", lastPayTime);
		json.put("goodsMark", goodsMark);
		json.put("ip", ip);
		json.put("payChannel", payChannel);
		System.out.println("请求报文:" + json.toString());
		// 报文提交
		String retStr = HttpUtilKeyVal.doPost(payUrl, json);
		System.out.println("应答报文:" + retStr);
		JSONObject retJson = JSONObject.parseObject(retStr);
		JSONObject data = retJson.getJSONObject("data");
		String url = data.getString("url");
		System.out.println("url是:" + url);
		return url;

	}
public static void main(String[] args) throws Exception {
	SaomaPay saomaPay=new SaomaPay();
	saomaPay.pay();
	System.out.println("111");
}
}
