package com.zspay.SDK.util;

import java.io.UnsupportedEncodingException;
import java.security.NoSuchAlgorithmException;
import java.util.Arrays;

import org.apache.log4j.Logger;

import com.alibaba.fastjson.JSONObject;

public final class Security {
	private static final Logger LOGGER = Logger.getLogger(Security.class);

	private Security() {

	}

	/**
	 * 构建签名原文
	 * 
	 * @param signFilds
	 * @param packet
	 * @return
	 */
	public static String orgSignSrc(String[] signFields, JSONObject packet) {
		if (signFields != null) {
			Arrays.sort(signFields); // 对key按照 字典顺序排序
		}

		StringBuffer signSrc = new StringBuffer("");
		int i = 0;
		for (String key : signFields) {
			signSrc.append(key);
			signSrc.append(Constants.EQUAL);
			signSrc.append((StringUtil.isEmpty(packet.getString(key)) ? "" : packet.getString(key)));
			// 最后一个元素后面不加&
			if (i < (signFields.length - 1)) {
				signSrc.append("&");
			}
			i++;
		}
		return signSrc.toString();
	}



	/**
	 * 
	 * @Title: countSignMd5
	 * @Description: 按照支付规范计算MD5签名
	 * @param md5Key
	 * @param signFields
	 * @param json
	 * @return
	 * @throws NoSuchAlgorithmException
	 * @throws UnsupportedEncodingException
	 * 
	 * @since 1.0
	 */
	public static String countSignMd5(String md5Key, String[] signFields, JSONObject json)
			throws NoSuchAlgorithmException, UnsupportedEncodingException {
		String signSrc = Security.orgSignSrc(signFields, json);
		signSrc += "&KEY=" + md5Key;
		System.out.println(signSrc);
		return MD5Encrypt.getMessageDigest(signSrc);
	}
	
	public static boolean verifySignMd5(String md5Key, String[] signFields, JSONObject json) throws NoSuchAlgorithmException, UnsupportedEncodingException {
		String sign = json.getString("sign");
		String countSign = countSignMd5(md5Key, signFields, json);
		LOGGER.info("sign["+sign+"]");
		LOGGER.info("countSign["+countSign+"]");
		return countSign.equals(sign);
	}
	
	public static void main(String[] args) throws NoSuchAlgorithmException, UnsupportedEncodingException {
		JSONObject json = new  JSONObject();
		json.put("merchantCode","1000000267");
		json.put("oriInstructCode","150211000000000019");
		json.put("returnInstructCode","150211000000000030");
		json.put("returnTransTime","20150211164511");
		json.put("amount","1");
		String s = countSignMd5("123456ABDDFF", new String[]{"merchantCode","oriInstructCode","returnInstructCode","returnTransTime","amount"}, json);
		json.put("sign", s);
		JSONObject d = new JSONObject();
		d.put("data", json);
		d.put("code", "00");
		d.put("msg", "");
		System.out.println(d);
		System.out.println(s);
	}
}
