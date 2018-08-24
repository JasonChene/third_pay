package com.sign.http;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class HttpHelper {
	/**
	 * 
	 * @param urls
	 *            请求地址
	 * @param Key
	 *            商户应用key
	 * @param timestamp
	 *            时间戳
	 * @param nonce
	 *            随机数
	 * @param signature
	 *            加密sign
	 * @param content
	 *            传输内容
	 * @return 服务器返回数据
	 */
	public static String getJsonData(String urls, String Key, String timestamp, String nonce, String signature,
			String content) {
		StringBuffer sb = new StringBuffer();
		try {
			// 创建url资源
			URL url = new URL(urls);
			// 建立http连接
			HttpURLConnection conn = (HttpURLConnection) url.openConnection();
			// 设置允许输出
			conn.setDoOutput(true);
			// 设置允许输入
			conn.setDoInput(true);
			// // 设置不用缓存
			conn.setUseCaches(false);
			// 设置传递方式
			conn.setRequestMethod("POST");

			// // 设置文件字符集:
			conn.setRequestProperty("Charset", "UTF-8");
			// 转换为字节数组
			byte[] data = (content.toString()).getBytes();
			// 设置文件长度
			conn.setRequestProperty("Content-Length", String.valueOf(data.length));

			conn.setRequestProperty("key", Key);
			conn.setRequestProperty("timestamp", timestamp);
			conn.setRequestProperty("nonce", nonce);
			conn.setRequestProperty("signature", signature);
			// 开始连接请求
			conn.connect();
			OutputStream out = new DataOutputStream(conn.getOutputStream());
			// 写入请求的字符串
			out.write((content.toString()).getBytes());
			out.flush();
			out.close();
			// 请求返回的状态
			if (HttpURLConnection.HTTP_OK == conn.getResponseCode()) {
				// 请求返回的数据
				InputStream in1 = conn.getInputStream();
				try {
					String readLine = new String();
					BufferedReader responseReader = new BufferedReader(new InputStreamReader(in1, "UTF-8"));
					while ((readLine = responseReader.readLine()) != null) {
						sb.append(readLine).append("\n");
					}
					responseReader.close();
				} catch (Exception e1) {
					e1.printStackTrace();
				}
			} else {
				System.out.println("error++"+conn.getResponseCode());
			}
		} catch (Exception e) {
			System.out.println(e);
		}
		return sb.toString();
	}
}
