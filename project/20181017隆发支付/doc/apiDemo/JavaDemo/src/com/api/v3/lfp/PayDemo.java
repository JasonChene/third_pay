package com.api.v3.lfp;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Map;
import java.util.TreeMap;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import net.sf.json.JSONObject;
import sun.misc.BASE64Decoder;
import sun.misc.BASE64Encoder;

/**
 * ֧��Demo
 * 
 */
public class PayDemo {

	static String merchNo = "LFP201808250000";// �̻���
	static String key = "7D7E768BB4CB7EBCE6E3B067A6351342";// ǩ��MD5��Կ,24λ
	static String reqUrl = "http://47.94.6.240:9003/api/pay";// ���Ի���
	static String queryUrl="http://47.94.6.240:9003/api/queryPayResult";
	static String version = "V3.6.0.0";// �汾��
	
	public static void main(String[] args) throws Throwable {
		pay();
		//payQuery();
	}
	
	
	/**
	 * ֧������
	 */
	public static void pay() throws Exception {
		Map<String, String> metaSignMap = new TreeMap<String, String>();
		String orderNo = new SimpleDateFormat("yyyyMMddHHmmssSSS").format(new Date()); // 20λ
		orderNo += ToolKit.randomStr(4);
		metaSignMap.put("orderNo", orderNo);
		metaSignMap.put("randomNo", ToolKit.randomStr(4));// 4λ�����

		metaSignMap.put("merchNo", merchNo);
		metaSignMap.put("netwayType", "ZFB");// WX:΢��֧��,ZFB:֧����֧��
		metaSignMap.put("amount", "500");// ��λ:��
		metaSignMap.put("goodsName", "iPhone���");// ��Ʒ���ƣ�20λ
		metaSignMap.put("notifyUrl", "http://127.0.0.1/");// �ص���ַ
		metaSignMap.put("notifyViewUrl", "http://127.0.0.1/view");// ���Ե�ַ

		String metaSignJsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(metaSignJsonStr + key, ToolKit.CHARSET);// 32λ
		System.out.println("sign=" + sign); // Ӣ����ĸ��д
		metaSignMap.put("sign", sign);

		byte[] dataStr = ToolKit.encryptByPublicKey(ToolKit.mapToJson(metaSignMap).getBytes(ToolKit.CHARSET),
				ToolKit.PAY_PUBLIC_KEY);
		String param = new BASE64Encoder().encode(dataStr);
		String reqParam = "data=" + URLEncoder.encode(param, ToolKit.CHARSET) + "&merchNo=" + merchNo + "&version=" + version;
		String resultJsonStr = ToolKit.request(reqUrl, reqParam);
		// ���״̬
		JSONObject resultJsonObj = JSONObject.fromObject(resultJsonStr);
		String stateCode = resultJsonObj.getString("stateCode");
		if (!stateCode.equals("00")) {
			return;
		}
		String resultSign = resultJsonObj.getString("sign");
		resultJsonObj.remove("sign");
		String targetString = ToolKit.MD5(resultJsonObj.toString() + key, ToolKit.CHARSET);
		if (targetString.equals(resultSign)) {
			System.out.println("ǩ��У��ɹ�");
		}
	}
	
	
	/**
	 * ֧����ѯ
	 * 
	 * @throws UnsupportedEncodingException
	 */
	public static void payQuery() throws UnsupportedEncodingException {
		Map<String, String> metaSignMap = new TreeMap<String, String>();
		metaSignMap.put("orderNo", "20180826092216382zV8ujN");
		metaSignMap.put("payDate", "2018-08-26");
		metaSignMap.put("merchNo", merchNo);
		metaSignMap.put("netwayType", "ZFB");// WX:΢��֧��,ZFB:֧����֧��
		metaSignMap.put("amount", "10000");// ��λ:��
		metaSignMap.put("goodsName", "�Ҿ�����");// ��Ʒ���ƣ�20λ

		String metaSignJsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(metaSignJsonStr + key, ToolKit.CHARSET);// 32λ
		System.out.println("sign=" + sign); // Ӣ����ĸ��д
		metaSignMap.put("sign", sign);

		byte[] dataStr = ToolKit.encryptByPublicKey(ToolKit.mapToJson(metaSignMap).getBytes(ToolKit.CHARSET),
				ToolKit.PAY_PUBLIC_KEY);
		String param = new BASE64Encoder().encode(dataStr);
		String reqParam = "data=" + URLEncoder.encode(param, ToolKit.CHARSET) + "&merchNo=" + merchNo + "&version=" + version;
		String resultJsonStr = ToolKit.request(queryUrl, reqParam);
		// ���״̬
		JSONObject resultJsonObj = JSONObject.fromObject(resultJsonStr);
		String stateCode = resultJsonObj.getString("stateCode");
		if (!stateCode.equals("00")) {
			return;
		}
		String resultSign = resultJsonObj.getString("sign");
		resultJsonObj.remove("sign");
		String targetString = ToolKit.MD5(resultJsonObj.toString() + key, ToolKit.CHARSET);
		if (targetString.equals(resultSign)) {
			System.out.println("ǩ��У��ɹ�");
		}
	}

	/**
	 * ֧���������
	 * 
	 * @throws Throwable
	 */
	public static void result(HttpServletRequest request, HttpServletResponse response) throws Throwable {
		String data = request.getParameter("data");
		byte[] result = ToolKit.decryptByPrivateKey(new BASE64Decoder().decodeBuffer(data), ToolKit.PRIVATE_KEY);
		String resultData = new String(result, ToolKit.CHARSET);// ��������

		JSONObject jsonObj = JSONObject.fromObject(resultData);
		Map<String, String> metaSignMap = new TreeMap<String, String>();
		metaSignMap.put("merchNo", jsonObj.getString("merchNo"));
		metaSignMap.put("netwayType", jsonObj.getString("netwayType"));
		metaSignMap.put("orderNo", jsonObj.getString("orderNo"));
		metaSignMap.put("amount", jsonObj.getString("amount"));
		metaSignMap.put("goodsName", jsonObj.getString("goodsName"));
		metaSignMap.put("payStateCode", jsonObj.getString("payStateCode"));// ֧��״̬
		metaSignMap.put("payDate", jsonObj.getString("payDate"));// yyyyMMddHHmmss
		String jsonStr = ToolKit.mapToJson(metaSignMap);
		String sign = ToolKit.MD5(jsonStr.toString() + key, ToolKit.CHARSET);
		if (!sign.equals(jsonObj.getString("sign"))) {
			return;
		}
		System.out.println("ǩ��У��ɹ�");
		response.getOutputStream().write("SUCCESS".getBytes());
	}

}
