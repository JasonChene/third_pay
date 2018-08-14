package com.zspay.netbank.demo.utils;

import java.security.KeyFactory;
import java.security.Signature;
import java.security.interfaces.RSAPrivateKey;
import java.security.spec.PKCS8EncodedKeySpec;
import java.util.Arrays;

import org.apache.commons.codec.binary.Base64;

import com.alibaba.fastjson.JSONObject;


public class Rsa {
	String KEY_ALGORITHM = "RSA";
	String SIGNATURE_ALGORITHM = "MD5withRSA";
	String RSA = "RSA";

	public RSAPrivateKey createRSAPrivateKey(String priKey) throws Exception {
		byte[] keyBytes = Base64.decodeBase64(priKey);
		PKCS8EncodedKeySpec keySpec = new PKCS8EncodedKeySpec(keyBytes);
		KeyFactory keyFactory = KeyFactory.getInstance(RSA);
		return (RSAPrivateKey) keyFactory.generatePrivate(keySpec);
	}

	public String byte2hex(byte[] b) {
		char[] digit = { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A',
				'B', 'C', 'D', 'E', 'F' };
		char[] out = new char[b.length * 2];
		for (int i = 0; i < b.length; i++) {
			byte c = b[i];
			out[i * 2] = digit[(c >>> 4) & 0X0F];
			out[i * 2 + 1] = digit[c & 0X0F];
		}

		return new String(out);
	}

	public String sign(String src, String pri) throws Exception {
		// 将明文数据转为byte数组
		byte[] data = src.getBytes("UTF-8");
		RSAPrivateKey priKey = createRSAPrivateKey(pri);
		// 实例化Signature
		Signature signature = Signature.getInstance(SIGNATURE_ALGORITHM);
		// 初始化Signature
		signature.initSign(priKey);
		// 更新
		signature.update(data);
		byte[] signB = signature.sign();
		return byte2hex(signB);
	}

	public boolean isEmpty(String str) {
		return str == null || str.isEmpty();
	}

	public String orgSignSrc(String[] signFields, JSONObject param) {
		if (signFields != null) {
			Arrays.sort(signFields); // 对key按照 字典顺序排序
		}

		StringBuffer signSrc = new StringBuffer("");
		int i = 0;
		for (String key : signFields) {
			signSrc.append(key);
			signSrc.append("=");
			signSrc.append(StringUtil.isEmpty(param.getString(key)) ? ""
					: param.getString(key));
			if (i < (signFields.length - 1)) { // 最后一个元素后面不加,
				signSrc.append("&");
			}
			i++;
		}
		return signSrc.toString();
	}
}
