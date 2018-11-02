package com.zhifu.pay.util;

import com.xiaoleilu.hutool.http.HttpRequest;

/**
 * HTTP 请求工具类
 *
 */
public class HttpClientUtil {
	
	
	public static String doPost(String url,String postRawStr) {
		String message = HttpRequest.post(url).timeout(30000)
				.contentType("application/json")
				.body(postRawStr)
				.execute().body();
		return message;
	}
}