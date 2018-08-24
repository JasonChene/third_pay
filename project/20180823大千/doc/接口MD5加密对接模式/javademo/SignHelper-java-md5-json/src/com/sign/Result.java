package com.sign;

import java.util.Map.Entry;
import java.util.Iterator;
import java.util.TreeMap;

import com.sign.config.Config;
import com.sign.utils.SignHelper;

import net.sf.json.JSONObject;

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
		JSONObject data = JSONObject.fromObject(str);
		TreeMap<String, String> map = new TreeMap<String, String>();
		Iterator iterator = data.keys();
		while (iterator.hasNext()) {
			String key = String.valueOf(iterator.next());
			String val = data.getString(key);
			if (!key.equals("signature")) {
				map.put(key, val);
			}
		}
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
