package com.zspay.SDK.util;

import java.io.BufferedInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import javax.servlet.http.HttpServletRequest;

import org.apache.log4j.Logger;

/**
 * 
 * 基础工具类

 
 */
public final class Utils {
	private Utils() {
		
	}
	private static final Logger LOGGER = Logger.getLogger(Utils.class);

	/**
	 * HTTP POST 发送数据
	 * 
	 * @param url
	 *            请求地址
	 * @param data
	 *            请求数据
	 * @return
	 * @throws Exception
	 */
	public static String sendHttpRequest(String url, String data) {
		// 创建链接
		HttpURLConnection hconn = null;
		OutputStream os = null;
		InputStream is = null;
		
		try {
			LOGGER.info( "["+url+"]["+data+"]");
			hconn = (HttpURLConnection) new URL(url).openConnection();
			hconn.setRequestMethod("POST"); // 设置为post请求
			hconn.setDoInput(true);
			hconn.setDoOutput(true);
			hconn.setUseCaches(false);
			hconn.setRequestProperty("Content-Type", "application/json");
			hconn.setConnectTimeout(10000); // 10s
			hconn.setReadTimeout(10000); // 10s

			// 发送数据
			os = hconn.getOutputStream();
			byte[] f = data.getBytes(Constants.ENCODE);
			os.write(f, 0, f.length);
			os.flush();
			os.close();
			os = null;

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
				recStr = new String(b, Constants.ENCODE);
			}

			LOGGER.info("应答：#0  "+ recStr);
			return recStr;
		} catch (Exception e) {
			LOGGER.error("应答：#0  ", e);
		} finally {
			if (hconn != null) {
				try {
					hconn.disconnect();
					hconn=null;
				} catch (Exception e) {
					LOGGER.error("", e);
					hconn=null;
				}
				
			}
			if (os != null) {
				try {
					os.close();
					os = null;
				} catch (Exception e) {
					LOGGER.error("", e);
					os = null;
				}
			}
			if (is != null) {
				try {
					is.close();
					is = null;
				} catch (IOException e) {
					LOGGER.error("", e);
					is = null;
				}
			}
		}
		return null;
	}
	
	public static String getPacket(HttpServletRequest request) {
		String packet = request.getParameter(Constants.PACKET);
		if (packet == null) {
			try {
				InputStream is = request.getInputStream();
				BufferedInputStream bis = new BufferedInputStream(is);
				// 最大读取b长度数据,如果数据流不足b长度,则当读到流截止符时,停止读取数据
				List<Byte> byteList = new ArrayList<Byte>();
				byte[] temp = new byte[1];
				while (bis.read(temp) != -1) {
					byteList.add(temp[0]);
				}
				int size = byteList.size();

				if (size > 0) {
					byte[] b = new byte[size];
					for (int i = 0; i < size; i++) {
						b[i] = byteList.get(i);
					}
					packet = new String(b, Constants.ENCODE);
				} else {
					LOGGER.info(" 从request中获取请求数据流中获取为空 ");
				}
			} catch (Exception e) {
				LOGGER.error("从request中获取请求数据流异常: " + e.toString(), e);
			}
		}
		LOGGER.info("从request获取数据URI[" + request.getRequestURI()+"]请求数据["+ packet+"]");
		return packet;
	}
}
