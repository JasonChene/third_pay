package com.sign;

import java.util.Map.Entry;
import java.util.TreeMap;

import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import com.sign.config.Config;
import com.sign.utils.SignHelper;

public class Result {

	public static void main(String[] args) {
		String str = "";
		
		signResult(str);
	}

	/**
	 * 订单验证
	 * 
	 * @param str
	 */
	private static void signResult(String str) {
		JsonObject jsonObject = new JsonParser().parse(str).getAsJsonObject();
		TreeMap<String, String> map = new TreeMap<String, String>();
		for (Entry<String, JsonElement> entry : jsonObject.entrySet()) {
			String key = entry.getKey();
			String val = jsonObject.getAsJsonObject().get(entry.getKey()).getAsString();
			System.out.println(key + ":" + val);
			if (!entry.getKey().equals("signature")) {
				map.put(key, val);
			}
		}
		// 空值不参与排序
		String signStr = SignHelper.sortSign(map) + Config.KEY;
		System.out.println("排序后拼接Key的字符串:" + signStr);
		String md5 = SignHelper.MD5(signStr);
		if (md5.equals(jsonObject.get("signature").getAsString())) {
			System.out.println("md5:" + md5 + ",匹配");
		} else {
			System.out.println("md5:" + md5 + "验证出错");
		}
	}
}
