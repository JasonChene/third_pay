package com.api.v3.xf;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Map;
import java.util.TreeMap;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.sun.org.apache.xml.internal.security.utils.Base64;

import net.sf.json.JSONObject;


/**
 * 代付
 * 
 * @author
 * 
 */
public class RemitDemo {
	static String merchNo = "XF201808160001";// 商户号
	static String key = "9416F3C0E62E167DA02DC4D91AB2B21E";// 签名MD5密钥,24位
	static String reqUrl = "http://127.0.0.1/api/remit";// 测试环境
	static String reqQueryUrl = "http://127.0.0.1/api/queryRemitResult";

	public static void main(String[] args) throws Exception {
		 remit();
		 //remitQuery();
	}

	
	/**
	 * 代付方法
	 * 
	 * @throws Exception
	 */
	public static void remit() throws Exception {
		Map<String, String> metaSignMap = new TreeMap<String, String>();

		String orderNo = new SimpleDateFormat("yyyyMMddHHmmssSSS").format(new Date()); // 20位
		orderNo += ToolKit.randomStr(3);
		metaSignMap.put("orderNo", orderNo);
		metaSignMap.put("version", "V3.3.0.0");// 版本号
		metaSignMap.put("charsetCode", ToolKit.CHARSET);// 编码

		// 需要经常修改的参数
		metaSignMap.put("bankCode", "ICBC");// 银行代码 参考对照表
		metaSignMap.put("merchNo", merchNo); // 商户号
		metaSignMap.put("bankAccountName", "陈先生");// 账户名
		metaSignMap.put("bankAccountNo", "6217582400001772678");// 银行卡号
		metaSignMap.put("amount", "1000");// 金额 单位:分
		metaSignMap.put("notifyUrl", "http://127.0.0.1/");// 支付结果通知地址

		String metaSignJsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(metaSignJsonStr + key, ToolKit.CHARSET);// 32位
		metaSignMap.put("sign", sign);
		byte[] dataStr = ToolKit.encryptByPublicKey(ToolKit.mapToJson(metaSignMap).getBytes(ToolKit.CHARSET),
				ToolKit.REMIT_PUBLIC_KEY);
		String param = Base64.encode(dataStr);
		String reqParam = "data=" + URLEncoder.encode(param, ToolKit.CHARSET) + "&merchNo=" + metaSignMap.get("merchNo");
		String resultJsonStr = ToolKit.request(reqUrl, reqParam);
		// 检查状态
		JSONObject resultJsonObj = JSONObject.fromObject(resultJsonStr);
		String stateCode = resultJsonObj.getString("stateCode");
		if (!stateCode.equals("00")) {
			return;
		}
		String resultSign = resultJsonObj.getString("sign");
		resultJsonObj.remove("sign");
		String targetString = ToolKit.MD5(resultJsonObj.toString() + key, ToolKit.CHARSET);
		if (targetString.equals(resultSign)) {
			System.out.println("签名校验成功");
		}
	}

	
	
	/**
	 * 代付查询
	 * 
	 * @throws UnsupportedEncodingException
	 */
	public static void remitQuery() throws UnsupportedEncodingException {
		Map<String, String> metaSignMap = new TreeMap<String, String>();
		metaSignMap.put("orderNo", "201808011041370902x0S");
		metaSignMap.put("remitDate", "2018-08-01");
		metaSignMap.put("merchNo", merchNo);
		metaSignMap.put("amount", "1000");// 单位:分

		String metaSignJsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(metaSignJsonStr + key, ToolKit.CHARSET);// 32位
		System.out.println("sign=" + sign); // 英文字母大写
		metaSignMap.put("sign", sign);

		byte[] dataStr = ToolKit.encryptByPublicKey(ToolKit.mapToJson(metaSignMap).getBytes(ToolKit.CHARSET),
				ToolKit.REMIT_PUBLIC_KEY);
		String param = Base64.encode(dataStr);
		String reqParam = "data=" + URLEncoder.encode(param, ToolKit.CHARSET) + "&merchNo=" + metaSignMap.get("merchNo");
		String resultJsonStr = ToolKit.request(reqQueryUrl, reqParam);
		// 检查状态
		JSONObject resultJsonObj = JSONObject.fromObject(resultJsonStr);
		String stateCode = resultJsonObj.getString("stateCode");
		if (!stateCode.equals("00")) {
			return;
		}
		String resultSign = resultJsonObj.getString("sign");
		resultJsonObj.remove("sign");
		String targetString = ToolKit.MD5(resultJsonObj.toString() + key, ToolKit.CHARSET);
		if (targetString.equals(resultSign)) {
			System.out.println("签名校验成功");
		}
	}

	/**
	 * 代付结果处理方法
	 * 
	 * @throws Throwable
	 */
	public static void remitResult(HttpServletRequest request, HttpServletResponse response) throws Throwable {
		String data = request.getParameter("data");

		byte[] result = ToolKit.decryptByPrivateKey(Base64.decode(data), ToolKit.PRIVATE_KEY);
		String resultData = new String(result, ToolKit.CHARSET);
		System.out.println("解密数据：" + resultData);

		JSONObject jsonObj = JSONObject.fromObject(resultData);
		Map<String, String> metaSignMap = new TreeMap<String, String>();
		metaSignMap.put("merchNo", jsonObj.getString("merchNo"));
		metaSignMap.put("orderNo", jsonObj.getString("orderNo"));
		metaSignMap.put("amount", jsonObj.getString("amount"));
		metaSignMap.put("remitStateCode", jsonObj.getString("remitStateCode"));
		metaSignMap.put("remitDate", jsonObj.getString("remitDate"));// yyyyMMddHHmmss
		String jsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(jsonStr.toString() + key, ToolKit.CHARSET);
		if (!sign.equals(jsonObj.getString("sign"))) {
			return;
		}
		System.out.println("签名校验成功");
		response.getOutputStream().write("SUCCESS".getBytes());
	}

	
}
