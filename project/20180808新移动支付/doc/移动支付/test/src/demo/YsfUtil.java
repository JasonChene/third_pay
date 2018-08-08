package demo;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.URL;
import java.net.URLConnection;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Map;

public class YsfUtil {
	
	private static final char[] DIGITS = { '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e','f' };
	
	public static String getSignStr(Map<String,String> map,String key){
		StringBuffer sb = new StringBuffer();
		sb.append("orderAmount="+map.get("orderAmount")+"&");
		sb.append("orderId="+map.get("orderId")+"&");
		sb.append("partner="+map.get("partner")+"&");
		sb.append("payMethod="+map.get("payMethod")+"&");
		sb.append("payType="+map.get("payType")+"&");
		sb.append("signType="+map.get("signType")+"&");
		sb.append("version="+map.get("version"));
		sb.append(key);
		return sb.toString();
		
	}
	
	public static String getReqUrl(Map<String,String> map,String reqUrl){
		StringBuffer sb = new StringBuffer();
		sb.append(reqUrl+"?");
		for(String key : map.keySet()){
			sb.append(key+"="+map.get(key)+"&");
		}
		return sb.toString().substring(0, sb.toString().lastIndexOf("&"));
	}
	

	/**
	 * 发送post请求
	 */ 
	public static String sendPost(String url, String param) throws Exception {
		PrintWriter out = null;
		BufferedReader in = null;
		String result = "";
		try {
			URL realUrl = new URL(url);
			URLConnection conn = realUrl.openConnection();
			conn.setRequestProperty("accept", "*/*");
			conn.setRequestProperty("connection", "Keep-Alive");
			conn.setRequestProperty("user-agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1;SV1)");
			conn.setDoOutput(true);
			conn.setDoInput(true);
			out = new PrintWriter(new OutputStreamWriter(conn.getOutputStream(), "UTF-8"));
			out.print(param);
			out.flush();
			in = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF-8"));
			String line;
			while ((line = in.readLine()) != null) {
				result += line;
			}
		} catch (Exception e) {
			throw new Exception("请求异常");
		}
		finally {
			try {
				if (out != null) {
					out.close();
				}
				if (in != null) {
					in.close();
				}
			} catch (IOException ex) {
				throw new Exception("请求异常");
			}
		}
		return result;
	}
	
	/**
	 * map转换为xml
	 * @param param
	 * @return
	 */
	public static String mapToXml(Map<String, String> param){
		StringBuffer sb = new StringBuffer();
		sb.append("<xml>");
		for(String key : param.keySet()){
			sb.append("<"+key+">"+param.get(key)+"</"+key+">");
		}
		sb.append("</xml>");
		return sb.toString();
	}
	
	/**
	 * md5
	 * @param content
	 * @return md5结果
	 * @throws Exception 
	 */
	public static String md5UTF8(String content) throws Exception {
		try {
			byte[] data = getMD5Digest().digest(content.getBytes("UTF-8"));
			char[] chars = encodeHex(data);
			return new String(chars);
		} catch (Exception ex) {
			throw new Exception("md5失败");
		}
	}
	
	private static MessageDigest getMD5Digest() {
		try {
			MessageDigest md5MessageDigest = MessageDigest.getInstance("MD5");
			md5MessageDigest.reset();
			return md5MessageDigest;
		} catch (NoSuchAlgorithmException nsaex) {
			return null;
		}
	}
	
	private static char[] encodeHex(byte[] data) {
		int l = data.length;
		char[] out = new char[l << 1];
		for (int i = 0, j = 0; i < l; i++) {
			out[j++] = DIGITS[(0xF0 & data[i]) >>> 4];
			out[j++] = DIGITS[0x0F & data[i]];
		}
		return out;
	}
}
