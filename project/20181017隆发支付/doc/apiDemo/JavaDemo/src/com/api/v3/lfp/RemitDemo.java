package com.api.v3.lfp;

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
	static String merchNo = "LFP201808250000";// 商户号
	static String key = "7D7E768BB4CB7EBCE6E3B067A6351342";// 签名MD5密钥,24位
	static String reqUrl = "http://47.94.6.240:9003/api/remit";// 测试环境
	static String reqQueryUrl = "http://47.94.6.240:9003/api/queryRemitResult";
	static String version = "V3.6.0.0";// 版本号

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

		// 需要经常修改的参数
		metaSignMap.put("bankCode", "BOC");// 银行代码 参考对照表
		metaSignMap.put("merchNo", merchNo); // 商户号
		metaSignMap.put("bankAccountName", "张三");// 账户名
		metaSignMap.put("bankAccountNo", "621661280000447287");// 银行卡号
		metaSignMap.put("amount", "1000");// 金额 单位:分
		metaSignMap.put("notifyUrl", "http://127.0.0.1/");// 支付结果通知地址

		String metaSignJsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(metaSignJsonStr + key, ToolKit.CHARSET);// 32位
		metaSignMap.put("sign", sign);
		byte[] dataStr = ToolKit.encryptByPublicKey(ToolKit.mapToJson(metaSignMap).getBytes(ToolKit.CHARSET),
				ToolKit.REMIT_PUBLIC_KEY);
		String param = Base64.encode(dataStr);
		String reqParam = "data=" + URLEncoder.encode(param, ToolKit.CHARSET) + "&merchNo=" + merchNo + "&version=" + version;
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
		metaSignMap.put("orderNo", "20180826101006258rnrZ");
		metaSignMap.put("remitDate", "2018-08-26");
		metaSignMap.put("merchNo", merchNo);
		metaSignMap.put("amount", "1000");// 单位:分

		String metaSignJsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(metaSignJsonStr + key, ToolKit.CHARSET);// 32位
		System.out.println("sign=" + sign); // 英文字母大写
		metaSignMap.put("sign", sign);

		byte[] dataStr = ToolKit.encryptByPublicKey(ToolKit.mapToJson(metaSignMap).getBytes(ToolKit.CHARSET),
				ToolKit.REMIT_PUBLIC_KEY);
		String param = Base64.encode(dataStr);
		String reqParam = "data=" + URLEncoder.encode(param, ToolKit.CHARSET) + "&merchNo=" + merchNo + "&version=" + version;
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
