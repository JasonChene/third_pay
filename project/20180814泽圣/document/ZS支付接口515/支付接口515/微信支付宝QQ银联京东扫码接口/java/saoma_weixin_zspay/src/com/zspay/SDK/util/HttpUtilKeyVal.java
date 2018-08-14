package com.zspay.SDK.util;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.SocketTimeoutException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;

import org.apache.commons.httpclient.ConnectTimeoutException;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.HttpStatus;
import org.apache.commons.httpclient.NameValuePair;
import org.apache.commons.httpclient.methods.GetMethod;
import org.apache.commons.httpclient.methods.PostMethod;
import org.apache.commons.httpclient.methods.StringRequestEntity;
import org.apache.commons.httpclient.params.HttpMethodParams;
import org.apache.log4j.Logger;

import com.alibaba.fastjson.JSONObject;

/**
 * http操作工具类
 * 
 * @author xu
 * 
 */
@SuppressWarnings("all")
public class HttpUtilKeyVal {
	/** 日志 */
	static Logger log = Logger.getLogger(HttpUtilKeyVal.class);

	private HttpUtilKeyVal() {
	}

	/** 因http请求容易阻塞，获取请求工具类时使用不同实例操作 */
	public static HttpUtilKeyVal getInstance() {
		return new HttpUtilKeyVal();
	}

	/**
	 * get请求并返回数据
	 * 
	 * @param url
	 * @return
	 */
	public static String getAndReceive(String url, Map<String, String> header) throws Exception {
		log.info("getAndReceive url=" + url);
		HttpClient client = null;
		GetMethod getMethod = null;
		try {
			client = new HttpClient();
			// 请求超时时间
			client.getHttpConnectionManager().getParams().setConnectionTimeout(10000);
			// 读取超时时间
			client.getHttpConnectionManager().getParams().setSoTimeout(10000);
			// 请求方法
			getMethod = new GetMethod(url);
			getMethod.setRequestHeader("Connection", "close");
			getMethod.addRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
			getMethod.getParams().setContentCharset("utf-8");
			getMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, "utf-8");
			// 设置请求头
			if (header != null) {
				for (Entry<String, String> e : header.entrySet()) {
					getMethod.setRequestHeader(e.getKey(), e.getValue());
				}
			}
			// 执行请求
			int statusCode = client.executeMethod(getMethod);
			// 检查http状态
			log.debug("getAndReceive statusCode=" + statusCode);
			if (statusCode != HttpStatus.SC_OK) {
				log.info("getAndReceive fail,status code=" + statusCode);
				getMethod.abort();
				return null;
			}
			// 获取返回信息
			return getMethod.getResponseBodyAsString();
		} catch (Exception e) {
			if (getMethod != null) {
				getMethod.abort();
			}
			e.printStackTrace();
			throw e;
		} finally {
			if (getMethod != null) {
				getMethod.releaseConnection();
			}
			log.debug("postAndReceive closed,url=" + url);
		}
	}

	/**
	 * post请求并返回数据
	 * 
	 * @param url
	 * @param data
	 * @return
	 */
	public String postAndReceive(String url, String data) throws Exception {
		log.info("postAndReceive,url=" + url + ",data=" + data);
		HttpClient client = null;
		PostMethod postMethod = null;
		try {
			client = new HttpClient();
			// 请求超时时间
			client.getHttpConnectionManager().getParams().setConnectionTimeout(10000);
			// 读取超时时间
			client.getHttpConnectionManager().getParams().setSoTimeout(10000);
			// 请求方法
			postMethod = new PostMethod(url);
			postMethod.setRequestHeader("Connection", "close");
			postMethod.addRequestHeader("Content-Type", "text/json;charset=UTF-8");
			postMethod.getParams().setContentCharset("utf-8");
			postMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, "utf-8");
			// 设置请求内容
			postMethod.setRequestEntity(new StringRequestEntity(data, "text/json", "UTF-8"));
			// 执行请求
			client.executeMethod(postMethod);
			// 检查http状态
			int statusCode = postMethod.getStatusLine().getStatusCode();
			// 检查http状态
			log.debug("postAndReceive statusCode=" + statusCode);
			if (statusCode != HttpStatus.SC_OK) {
				log.info("postAndReceive fail,status code=" + statusCode);
				postMethod.abort();
				return null;
			}
			// 读取返回数据
			InputStream resStream = postMethod.getResponseBodyAsStream();
			BufferedReader br = new BufferedReader(new InputStreamReader(resStream));
			StringBuffer resBuffer = new StringBuffer();
			String resTemp = "";
			while ((resTemp = br.readLine()) != null) {
				resBuffer.append(resTemp);
			}
			return resBuffer.toString();
		} catch (Exception e) {
			if (postMethod != null) {
				postMethod.abort();
			}
			e.printStackTrace();
			throw e;
		} finally {
			if (postMethod != null) {
				postMethod.releaseConnection();
			}
			log.debug("postAndReceive closed,url=" + url);
		}
	}

	/**
	 * post请求并返回数据,data会被封装到packet中
	 * 
	 * @param url
	 * @param data
	 * @return
	 */
	public String postAndReceivePacket(String url, String data) throws Exception {
		log.info("postAndReceivePacket,url=" + url + ",data=" + data);
		HttpClient client = null;
		PostMethod postMethod = null;
		try {
			client = new HttpClient();
			// 请求超时时间
			client.getHttpConnectionManager().getParams().setConnectionTimeout(10000);
			// 读取超时时间
			client.getHttpConnectionManager().getParams().setSoTimeout(10000);
			// 请求方法
			postMethod = new PostMethod(url);
			postMethod.setRequestHeader("Connection", "close");
			postMethod.addRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
			postMethod.getParams().setContentCharset("utf-8");
			postMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, "utf-8");
			// 设置请求内容
			NameValuePair[] dataArray = new NameValuePair[] { new NameValuePair("PACKET", data) };
			postMethod.setRequestBody(dataArray);
			// 执行请求
			client.executeMethod(postMethod);
			// 检查http状态
			int statusCode = postMethod.getStatusLine().getStatusCode();
			// 检查http状态
			log.debug("postAndReceivePacket statusCode=" + statusCode);
			if (statusCode != HttpStatus.SC_OK) {
				log.info("postAndReceivePacket fail,status code=" + statusCode);
				postMethod.abort();
				return null;
			}
			// 读取返回数据
			InputStream resStream = postMethod.getResponseBodyAsStream();
			BufferedReader br = new BufferedReader(new InputStreamReader(resStream));
			StringBuffer resBuffer = new StringBuffer();
			String resTemp = "";
			while ((resTemp = br.readLine()) != null) {
				resBuffer.append(resTemp);
			}
			return resBuffer.toString();
		} catch (Exception e) {
			postMethod.abort();
			throw e;
		} finally {
			if (postMethod != null) {
				postMethod.releaseConnection();
			}
			log.debug("postAndReceive closed,url=" + url);
		}
	}

	
	
	public static String doPost(String url, Map<String, String> params, Map<String, String> header) {
		// 构建请求参数
		StringBuffer sb = new StringBuffer();
		if (params != null) {
			boolean isFirst = false;
			for (Entry<String, String> e : params.entrySet()) {
				if (isFirst) {
					sb.append("&");
				}
				sb.append(e.getKey());
				sb.append("=");
				sb.append(e.getValue());
				if (!isFirst) {
					isFirst = true;
				}
			}
			// sb.substring(0, sb.length() - 2);
		}
		System.out.println("url     : " + url);
		System.out.println("header  : " + header);
		System.out.println("data    : " + sb.toString());

		// 使用POST方法
		String reciveStr = null;
		try {
			HttpClient httpClient = new HttpClient();
			PostMethod postMethod = new PostMethod(url);
			httpClient.getHttpConnectionManager().getParams().setConnectionTimeout(80000);
			httpClient.getHttpConnectionManager().getParams().setSoTimeout(80000);
			postMethod.setRequestHeader("Connection", "close");
			postMethod.addRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
			postMethod.getParams().setContentCharset("utf-8");
			postMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, "utf-8");
			// 填入各个表单域的值
			NameValuePair[] dataList = null;
			if (params != null) {
				dataList = new NameValuePair[params.keySet().size()];
				int i = 0;
				for (Entry<String, String> e : params.entrySet()) {
					dataList[i] = new NameValuePair(e.getKey(), e.getValue());
					i++;
				}
				postMethod.setRequestBody(dataList);
			} else {
				postMethod.setRequestBody("");
			}
			// 将表单的值放入postMethod中
			if (header != null) {
				for (Entry<String, String> e : header.entrySet()) {
					postMethod.setRequestHeader(e.getKey(), e.getValue());
				}
			}
			// 执行postMethod
			int statusCode = httpClient.executeMethod(postMethod);

			// 打印服务器返回的状态
			System.out.println("code    : " + statusCode);
			reciveStr = postMethod.getResponseBodyAsString();
			// 释放连接
			postMethod.releaseConnection();
		} catch (HttpException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return reciveStr;
	}

	public static String doPost(String url, JSONObject params) throws Exception{
		// 构建请求参数
		System.out.println("url     : " + urlMap.get(flag)+ url);
		System.out.println("data    : " + params);

		// 使用POST方法
		String reciveStr = null;
		try {
			HttpClient httpClient = new HttpClient();
			PostMethod postMethod = new PostMethod(urlMap.get(flag)+ url);
			httpClient.getHttpConnectionManager().getParams().setConnectionTimeout(80000);
			httpClient.getHttpConnectionManager().getParams().setSoTimeout(80000);
			postMethod.setRequestHeader("Connection", "close");
			postMethod.addRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
			postMethod.getParams().setContentCharset("utf-8");
			postMethod.getParams().setParameter(HttpMethodParams.HTTP_CONTENT_CHARSET, "utf-8");
			// 填入各个表单域的值
			postMethod.addParameter("data", params.toString());
			
			NameValuePair[] dataList = null;
			dataList = new NameValuePair[params.keySet().size()];
			int i = 0;
			Set<String> keys = params.keySet();
			Iterator<String> it = keys.iterator();
			while(it.hasNext()) {
				String key = it.next();
				dataList[i] = new NameValuePair(key, params.getString(key));
				
				i++;
			}
			postMethod.setRequestBody(dataList);
			
//			// 将表单的值放入postMethod中
//			if (header != null) {
//				for (Entry<String, String> e : header.entrySet()) {
//					postMethod.setRequestHeader(e.getKey(), e.getValue());
//				}
//			}
			// 执行postMethod
			int statusCode = httpClient.executeMethod(postMethod);

			// 打印服务器返回的状态
			System.out.println("code    : " + statusCode);
			reciveStr = postMethod.getResponseBodyAsString();
			// 释放连接
			postMethod.releaseConnection();
		}catch(ConnectTimeoutException e){
			System.out.println("连接超时");
			throw e;
		}catch(SocketTimeoutException e){
			System.out.println("线程超时");
			throw e;
		} catch (HttpException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		return reciveStr;
	}
	
	private static String flag = "3";
	private static Map<String, String> urlMap = new HashMap<String, String>();
	static {
		
		
		// 正式环境
		urlMap.put("3", "http://payment.zsagepay.com/");
		
		
		
	}
}
