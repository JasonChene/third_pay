package com.zspay.SDK.util;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.log4j.Logger;

/**
 * 
 * 
 * @Classname：HttpUtils

 
 * 
 * @version V1.0
 * 
 */
public class HttpUtils {
	/** 平台标识 0-本地,1-开发,2-测试，3-正式 ，4-测试pos地址,5消费撤销正式地址;传入其他值表示可以任意地址 */
	public static final String PLATFLG = "3";
	public static final String PLATFLG1 = "5";
	private static Logger log = Logger.getLogger(HttpUtils.class);
	/**
	 * 
	 * @Title: sendHttpRequest
	 * @Description: 发送地址
	 * @param platFlg
	 *            :0-本地,1-开发,2-测试，3-正式
	 * @param url
	 * @param data
	 * @return
	 * @throws Exception
	 * 
	 * @since 1.0
	 */
	public static String sendHttpRequest(String url, String data) throws Exception {
		return sendHttpRequest(url, data, "");
	}
	public static String sendHttpRequest1(String url, String data) throws Exception {
		return sendHttpRequest1(url, data, "");
	}
	/**
	 * 
	 * @Title: sendHttpRequest
	 * @Description: 发送地址
	 * @param platFlg
	 *            :0-本地,1-开发,2-测试，3-正式
	 * @param url
	 * @param data
	 * @param mode
	 * @return
	 * @throws Exception
	 * 
	 * @since 1.0
	 */
	public static String sendHttpRequest(String url, String data, String mode) throws Exception {
		log.info(mode + "-请求:" + data);
		// 创建链接
		HttpURLConnection hconn = null;
		OutputStream os = null;
		InputStream is = null;
		try {
			hconn = (HttpURLConnection) new URL(urlMap.get(PLATFLG) + url).openConnection();
			hconn.setRequestMethod("POST"); // 设置为post请求
			hconn.setDoInput(true);
			hconn.setDoOutput(true);
			hconn.setUseCaches(false);
			hconn.setRequestProperty("Content-Type", "application/json");
			hconn.setConnectTimeout(30000); // 30s
			hconn.setReadTimeout(30000); // 30s
			log.info("请求地址:" + urlMap.get(PLATFLG) + url);
			System.out.println("请求地址:" + urlMap.get(PLATFLG) + url);
			// 发送数据
			os = hconn.getOutputStream();
			byte[] f = data.getBytes("utf-8");
			os.write(f, 0, f.length);
			os.flush();
			// 接收数据
			is = hconn.getInputStream();
			List<Byte> byteList = new ArrayList<Byte>();
			byte[] buf = new byte[1];
			while ((is.read(buf)) > 0) {
				byteList.add(buf[0]);
			}
			is.close();
			is = null;
			hconn.disconnect();
			hconn = null;

			String recStr = "";
			int size = byteList.size();
			if (size > 0) {
				byte[] b = new byte[size];
				for (int i = 0; i < size; i++) {
					b[i] = byteList.get(i);
				}
				recStr = new String(b, "utf-8");
			}
			log.info(mode + "-响应:" + recStr);
			return recStr;
		} catch (Exception e) {
			log.error(mode + "-请求失败", e);
			throw e;
		} finally {
			if (hconn != null) {
				hconn.disconnect();
			}
			if (os != null) {
				try {
					os.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
			if (is != null) {
				try {
					is.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		}

	}
	//为了区分  是否需要地址
	public static String sendHttpRequest1(String url, String data, String mode) throws Exception {
		log.info(mode + "-请求:" + data);
		// 创建链接
		
		
		HttpURLConnection hconn = null;
		OutputStream os = null;
		InputStream is = null;
		try {
			hconn = (HttpURLConnection) new URL(urlMap.get(PLATFLG1) + url).openConnection();
			hconn.setRequestMethod("POST"); // 设置为post请求
			hconn.setDoInput(true);
			hconn.setDoOutput(true);
			hconn.setUseCaches(false);
			hconn.setRequestProperty("Content-Type", "application/json");
			hconn.setConnectTimeout(30000); // 30s
			hconn.setReadTimeout(30000); // 30s
			log.info("请求地址:" + urlMap.get(PLATFLG) + url);
			// 发送数据
			os = hconn.getOutputStream();
			byte[] f = data.getBytes("utf-8");
			os.write(f, 0, f.length);
			os.flush();
			// 接收数据
			is = hconn.getInputStream();
			List<Byte> byteList = new ArrayList<Byte>();
			byte[] buf = new byte[1];
			while ((is.read(buf)) > 0) {
				byteList.add(buf[0]);
			}
			is.close();
			is = null;
			hconn.disconnect();
			hconn = null;

			String recStr = "";
			int size = byteList.size();
			if (size > 0) {
				byte[] b = new byte[size];
				for (int i = 0; i < size; i++) {
					b[i] = byteList.get(i);
				}
				recStr = new String(b, "utf-8");
			}
			log.info(mode + "-响应:" + recStr);
			return recStr;
		} catch (Exception e) {
			log.error(mode + "-请求失败", e);
			throw e;
		} finally {
			if (hconn != null) {
				hconn.disconnect();
			}
			if (os != null) {
				try {
					os.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
			if (is != null) {
				try {
					is.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		}

	}
	private static Map<String, String> urlMap = new HashMap<String, String>();
	static {
		urlMap.put("-1", "");
		
		// 正式环境
		urlMap.put("3", "");
		
	}
}
