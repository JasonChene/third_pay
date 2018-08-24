package com.sign;

import java.util.Map;
import java.util.TreeMap;

import com.alibaba.fastjson.JSONObject;
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

		JSONObject data = JSONObject.parseObject(str);
		TreeMap<String, String> map = new TreeMap<String, String>();
		for (Map.Entry<String, Object> entry : data.entrySet()) {
			System.out.println(entry.getKey() + ":" + entry.getValue());
			if (!entry.getKey().equals("signature")) {
				map.put(entry.getKey(), entry.getValue().toString());
			}
		}
		// 空值不参与排序
		String signStr = SignHelper.sortSign(map) + Config.KEY;
		System.out.println("排序后拼接Key的字符串:" + signStr);
		String md5 = SignHelper.MD5(signStr);
		if (md5.equals(data.getString("signature"))) {
			System.out.println("md5:" + md5 + ",匹配");
		} else {
			System.out.println("md5:" + md5 + "验证出错");
		}
	}
}
