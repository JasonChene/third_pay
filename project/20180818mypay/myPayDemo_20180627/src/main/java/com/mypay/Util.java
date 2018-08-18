package com.mypay;

import java.security.MessageDigest;

import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;
public class Util {

	private static final Logger LOG = LogManager.getLogger(Util.class);

	public static String md5(String target) {

		try {
			MessageDigest md5 = MessageDigest.getInstance("MD5");
			md5.reset();
			byte[] signByte = md5.digest(target.getBytes("UTF-8"));
			StringBuffer ret = new StringBuffer(signByte.length);
			String hex = "";
			for (int i = 0; i < signByte.length; i++) {
				hex = Integer.toHexString(signByte[i] & 0xFF);

				if (hex.length() == 1) {
					hex = '0' + hex;
				}
				ret.append(hex.toUpperCase());
			}

			String sign = ret.toString();
			sign = sign.toUpperCase();

			return sign;
		} catch (Exception e) {
			e.printStackTrace();
		}

		return null;
	}

}
