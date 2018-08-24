package com.sign.utils;

import com.sign.config.Config;

public class LogHelpers {
	/**
	 * 请求通用加密输出日志
	 * 
	 * @param nonce
	 *            随机字符串
	 * @param timeStamp
	 *            时间戳
	 * @param data
	 *            key的字母排序asc结果
	 * @param signature
	 *            header的signature结果
	 * @param jsonString
	 *            排序后需加密的JSON字符串
	 * @param content
	 *            实际Post发送的content
	 */
	public static void Sysos(String nonce, long timeStamp, String data, String signature, String jsonString,
			String content) {
		System.out.println("key的字母排序asc结果:" + data);
		System.out.println("获得的signature结果:" + signature);
		System.out.println("排序后需加密的JSON字符串:" + jsonString);
		System.out.println("时间戳:" + timeStamp + "\n应用KEY:" + Config.KEY + "\n随机字符串:" + nonce);
		System.out.println("发送的content:" + content);
	}

}
