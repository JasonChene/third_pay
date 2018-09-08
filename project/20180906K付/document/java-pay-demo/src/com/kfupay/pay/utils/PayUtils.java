package com.kfu.pay.utils;

import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

import org.apache.commons.lang.StringUtils;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;

/**
 * 工具类
 */

public class PayUtils {
	//编码格式UTF-8
	public static final String CHARSET 			= "UTF-8";
	
	//生成RSA签名：sign
	public static String buildSign(JSONObject json,String merKey) {
		StringBuffer buffer		= new StringBuffer();
		TreeMap<String, Object> treeMap = new TreeMap<String, Object>();
		for (Map.Entry<String, Object> entry : json.entrySet()) {
			if (entry.getValue() != null) {
				treeMap.put(entry.getKey(), entry.getValue());
			}
		}
		for (Map.Entry<String, Object> entry : treeMap.entrySet()) {
			if (entry.getValue() != null) {
				buffer.append(entry.getValue());
			}
		}
		buffer.append(merKey);
		return HashUtil.sha1(buffer.toString());
	}

	//生成密文：data
	public static String buildData(JSONObject json, String merKey) {
		return AES.encryptWithKeyBase64(json.toJSONString(), merKey);
	}
	
	//解密data，获得明文参数
	public static JSONObject decrypt(String data, String merKey) {
		JSONObject result	= null;
		//2.使用AESKey解开data，取得明文参数；解密后格式为json
		String jsonStr = AES.decryptWithKeyBase64(data, merKey);
		result = JSON.parseObject(jsonStr);
		return result;
	}
	
	// sign验签
	public static boolean checkSign(JSONObject json,String merKey) {
		//获取明文参数中的sign。
		String signPay	= StringUtils.trimToEmpty(json.getString("sign"));
		//将明文参数中sign之外的其他参数，拼接成字符串
		StringBuffer buffer	= new StringBuffer();
		TreeMap<String, Object> treeMap = new TreeMap<String, Object>();
		for (Map.Entry<String, Object> entry : json.entrySet()) {
			if (entry.getValue() != null) {
				treeMap.put(entry.getKey(), entry.getValue());
			}
		}
		for(Entry<String, Object> entry : treeMap.entrySet()) {
			String key		= formatStr(entry.getKey());
			String value	= formatStr(entry.getValue());
			if("sign".equals(key)) {
				continue;
			}
			buffer.append(value);
		}
		buffer.append(merKey);
		//result为true时表明验签通过
		String checkSign = HashUtil.sha1(buffer.toString());
		return signPay.equals(checkSign);
	}

	//一键支付post请求
	public static JSONObject httpPost(String url, String merAccount, String data) {
		//请求参数为如下：merAccount、data
		Map<String, String> paramMap	= new HashMap<String, String>();
		paramMap.put("data", data);
		paramMap.put("merAccount", merAccount);
		String responseBody	= HttpClient4Utils.sendHttpRequest(url, paramMap, CHARSET, true);
		return JSON.parseObject(responseBody);
	}
	
	//get请求
	public static JSONObject httpGet(String url, String merAccount, String data) {
		//请求参数为如下：merAccount、data
		Map<String, String> paramMap	= new HashMap<String, String>();
		paramMap.put("data", data);
		paramMap.put("merAccount", merAccount);
		String responseBody	= HttpClient4Utils.sendHttpRequest(url, paramMap, CHARSET, false);
		return JSON.parseObject(responseBody);
	}
	
	//字符串格式化
	public static String formatStr(Object text) {
		return (text == null) ? "" : (text+"").trim();
	}

	//test
	public static void main(String[] args) {
		//TreeMap<String, String> responseDataMap = decrypt("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");
	    //System.out.println(responseDataMap);
	}
}
