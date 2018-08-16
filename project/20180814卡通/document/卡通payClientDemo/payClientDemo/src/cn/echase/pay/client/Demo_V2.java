package cn.echase.pay.client;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.security.MessageDigest;
import java.util.HashMap;
import java.util.Map;
import java.util.SortedMap;
import java.util.TreeMap;
import java.util.UUID;
import java.util.Map.Entry;

import mjson.Json;

import org.apache.commons.codec.binary.Base64;

public class Demo_V2 {
	/**
	 * 默认的http连接超时时间
	 */
	private final static int DEFAULT_CONN_TIMEOUT = 10000; // 10s
	/**
	 * 默认的http read超时时间
	 */
	private final static int DEFAULT_READ_TIMEOUT = 120000; // 120s

	private final static String[] hexDigits = { "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d",
			"e", "f" };

	/**
	 * 转换字节数组为16进制字串
	 * 
	 * @param b
	 *            字节数组
	 * @return 16进制字串
	 */
	public static String byteArrayToHexString(byte[] b) {
		StringBuilder resultSb = new StringBuilder();
		for (byte aB : b) {
			resultSb.append(byteToHexString(aB));
		}
		return resultSb.toString();
	}

	/**
	 * 转换byte到16进制
	 * 
	 * @param b
	 *            要转换的byte
	 * @return 16进制格式
	 */
	private static String byteToHexString(byte b) {
		int n = b;
		if (n < 0) {
			n = 256 + n;
		}
		int d1 = n / 16;
		int d2 = n % 16;
		return hexDigits[d1] + hexDigits[d2];
	}

	/**
	 * MD5计算
	 * 
	 * @param origin
	 *            原始字符串
	 * @return 经过MD5加密之后的结果
	 */
	public static String MD5Encode(String origin) {
		// origin =
		// "ibslocdate=20170111164548&signMethod=02&std400chnl=6005&std400memo=test&std400mgid=6002&stdbegtime=20170111161540&stdmercno=996600008000084&stdmsgtype=0210&stdorderid=20170111161540148412254033031308&stdpaytype=02&stdprocode=481000&stdrefnum=9012017011116244548132485&stdrtninfo=支付授权已过期，请刷新再试&stdtermid=1&stdtranamt=1&stdtrancur=156&key=85954374796076156844667021611959";
		// System.out.println("---ssssss-");
		String resultString = null;
		try {
			resultString = origin;
			// resultString = MD5Utils.MD5Encode(resultString);
			MessageDigest md = MessageDigest.getInstance("MD5");
			md.update(resultString.getBytes("UTF-8"));
			resultString = byteArrayToHexString(md.digest());
		} catch (Exception e) {
			e.printStackTrace();
		}
		// System.out.println("-xx-"+resultString);
		return resultString;
	}

	public byte[] base64Encode(byte[] inputByte) throws IOException {
		return Base64.encodeBase64(inputByte);
	}
	/**
	 * 获取报文json字符串
	 * @param key
	 * @param reqMap
	 * @return
	 */
    public String getJsonString(String key,Map<String, String> reqMap){
    	// 将reqMap排序
		SortedMap<String, String> sm = new TreeMap<String, String>(reqMap);
		// 按序拼接
		StringBuilder sb = new StringBuilder();
		for (Entry<String, String> sme : sm.entrySet()) {
			String v = sme.getValue();
			// 空字段不参加签名
			if (null == v || v.length() == 0)
				continue;
			sb.append("&").append(sme.getKey()).append("=").append(v.trim());
		}
		// System.out.println(sb.substring(1));

		// 尾部加上md5key签名
		sb.append("&key=").append(key);

		//System.out.println("发送加签报文：" + sb.substring(1));
		try {
			// String sbstr =new String(sb.substring(1).getBytes("utf-8"));
			String sign = MD5Encode(sb.substring(1)).toUpperCase();
			// System.out.println("本地加签后的："+sign);
			// 将签名信息加入原始请求报文map
			reqMap.put("sign", sign);
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
		// 将Map转成Json
		Json reqJs = Json.make(reqMap);
		// 生成json字符串
		String reqStr = reqJs.toString();
		return reqStr;
    }
	/**
	 * 对Map报文进行签名，并发送
	 * 
	 * @param reqMap
	 * @return
	 */
	public String request(String url, String key, Map<String, String> reqMap) {
		// 将reqMap排序
		SortedMap<String, String> sm = new TreeMap<String, String>(reqMap);
		// 按序拼接
		StringBuilder sb = new StringBuilder();
		for (Entry<String, String> sme : sm.entrySet()) {
			String v = sme.getValue();
			// 空字段不参加签名
			if (null == v || v.length() == 0)
				continue;
			sb.append("&").append(sme.getKey()).append("=").append(v.trim());
		}
		// System.out.println(sb.substring(1));

		// 尾部加上md5key签名
		sb.append("&key=").append(key);

		System.out.println("发送加签报文：" + sb.substring(1));
		try {
			// String sbstr =new String(sb.substring(1).getBytes("utf-8"));
			String sign = MD5Encode(sb.substring(1)).toUpperCase();
			// System.out.println("本地加签后的："+sign);
			// 将签名信息加入原始请求报文map
			reqMap.put("sign", sign);
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
		System.out.println(reqMap);
		// 将Map转成Json
		Json reqJs = Json.make(reqMap);
		// 生成json字符串
		String reqStr = reqJs.toString();
		String respStr = postReq(url, reqStr);
		return respStr;
	}

	/**
	 * 解析返回的报文，并验签:
	 * 
	 * @param finalRespStr
	 * @return
	 */
	public static Map<String, Object> getResp(String key, String respJsStr) {
		
		System.out.println("接收到的完整报文内容：" + respJsStr);
		// 解析json
		Json respJs = Json.read(respJsStr);
		String status =  respJs.at("status").toString();
		//tring result_code=  respJs.at("result_code").toString();
		Map<String, Object> map = new HashMap<String, Object>();
		
			// 转成map方便排序
			SortedMap<String, Object> sm = new TreeMap<String, Object>(respJs.asMap());
			// 按序拼接
			StringBuilder sb = new StringBuilder();
			for (Entry<String, Object> sme : sm.entrySet()) {
				// 排除sign字段
				if ("sign".equals(sme.getKey()))
					continue;
				String v = (String) sme.getValue();
				// 空字段不参加验签
				if (null == v || v.length() == 0)
					continue;
				sb.append("&").append(sme.getKey()).append("=").append(v);
			}

			// 尾部加上md5key签名
			sb.append("&key=").append(key);
			//System.out.println("返回加签报文：" + sb.substring(1));
			
			try {
				// String sbstr =new String(sb.substring(1).getBytes("utf-8"));
				String sign = MD5Encode(sb.substring(1)).toUpperCase();
				//System.out.println("本地加签后的：" + sign);
				String respSign = respJs.at("sign").toString();
				respSign = respSign.substring(1, respSign.length() - 1);
				//System.out.println("接收报文中的：" + respSign);

				if (respSign.equals(sign)) {
					// map.put("sign_res", "1");
					//System.out.println("md5 OK!");
				} else {
					// map.put("sign_res", "0");
					//System.out.println("md5 ERROR!");
					map.put("status", "1") ;
					map.put("message",  "验签失败");
					return map;
				}
				// respJs.add(map);

			} catch (Exception e) {
				throw new RuntimeException(e);
			}
			return respJs.asMap();
		
		
	}

	/**
	 * 解析返回的报文，并验签:
	 * 
	 * @param finalRespStr
	 * @return
	 */
	public boolean sign(String key, String finalRespStr) {
		assert finalRespStr.startsWith("sendData=");
		String respB64Str = finalRespStr.substring(9);
		// base64解码,并对一些特殊字符进行置换
		byte[] respJsBs = Base64.decodeBase64(respB64Str.replaceAll("#", "+"));

		StringBuffer sbuf = new StringBuffer();
		for (int i = 0; i < respJsBs.length; i++) {
			sbuf.append(respJsBs[i]);
		}

		String respJsStr = sbuf.toString();
		try {
			respJsStr = new String(respJsBs, "utf-8");
		} catch (UnsupportedEncodingException e) {
			throw new RuntimeException(e);
		}
		// 解析json
		Json respJs = Json.read(respJsStr);
		// 转成map方便排序
		SortedMap<String, Object> sm = new TreeMap<String, Object>(respJs.asMap());
		// 按序拼接
		StringBuilder sb = new StringBuilder();
		for (Entry<String, Object> sme : sm.entrySet()) {
			// 排除sign字段
			if ("sign".equals(sme.getKey()))
				continue;
			String v = (String) sme.getValue();
			// 空字段不参加验签
			if (null == v || v.length() == 0)
				continue;
			sb.append("&").append(sme.getKey()).append("=").append(v);
		}

		// 尾部加上md5key签名
		sb.append("&key=").append(key);
		// System.out.println("加签报文："+sb.substring(1));
		try {
			String sign = MD5Encode(sb.substring(1)).toUpperCase();
			// System.out.println("本地加签后的："+sign);
			String respSign = respJs.at("sign").toString();
			respSign = respSign.substring(1, respSign.length() - 1);
			if (respSign.equals(sign)) {
				// System.out.println("md5 OK!");
				return true;
			} else {
				// System.out.println("md5 ERROR!");
				return false;
			}

		} catch (Exception e) {
			// throw new RuntimeException(e);
			e.printStackTrace();
			return false;
		}
	}

	/**
	 * http post,有返回String
	 * 
	 * @param requrl
	 * @param req
	 * @param connTimeOut
	 * @param readTimeOut
	 * @return
	 */
	public String postReq(String requrl, String req, int connTimeOut, int readTimeOut) {
		try {
			HttpURLConnection conn = null;
			try {
				URL url = new URL(requrl);
				conn = (HttpURLConnection) url.openConnection();
				conn.setDoInput(true);
				conn.setDoOutput(true); // POST
				conn.setRequestMethod("POST");
				conn.setUseCaches(false);
				conn.setConnectTimeout(connTimeOut);
				conn.setReadTimeout(readTimeOut);
				 conn.setRequestProperty("Content-Type",  
                 "application/json");  
				conn.connect();
			} catch (Exception e) {
				throw new RuntimeException(e);
			}

			OutputStreamWriter out = new OutputStreamWriter(conn.getOutputStream(), "utf-8");
			out.write(req);
			System.out.println(req);
			out.flush();
			out.close();

			BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream(), "utf-8"));
			StringBuilder sb = new StringBuilder();
			char[] buff = new char[2048];
			int cnt = 0;
			while ((cnt = in.read(buff)) != -1)
				sb.append(buff, 0, cnt);
			in.close();
			String rtStr = sb.toString();
			return rtStr;
		} catch (IOException e) {
			//System.out.println(e);
			throw new RuntimeException(e);
		}
	}

	/**
	 * 标准http post,有返回String
	 * 
	 * @param requrl
	 * @param req
	 * @return
	 */
	public String postReq(String url, String req) {
		return postReq(url, req, DEFAULT_CONN_TIMEOUT, DEFAULT_READ_TIMEOUT);
	}
	public static String getSendData(String key,Map<String, String> reqMap){
		//将reqMap排序
		SortedMap<String, String> sm = new TreeMap<String, String>(reqMap);
		//按序拼接
		StringBuilder sb = new StringBuilder();
		for(Entry<String, String> sme : sm.entrySet()){
			String v = sme.getValue();
			//空字段不参加签名
			if(null == v || v.length()==0)
				continue;
			sb.append("&").append(sme.getKey()).append("=").append(v.trim());
		}
		//System.out.println(sb.substring(1));
		
		
		//尾部加上md5key签名		
		sb.append("&key=").append(key);
		
		//System.out.println("发送加签报文："+sb.substring(1));
		try {
			//String sbstr =new String(sb.substring(1).getBytes("utf-8"));
			String signAture = MD5Encode(sb.substring(1)).toUpperCase();
			//System.out.println("本地加签后的："+signAture);
			//将签名信息加入原始请求报文map
			reqMap.put("sign", signAture);
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
		//将Map转成Json
		Json reqJs = Json.make(reqMap);
		//生成json字符串
		String reqStr = reqJs.toString();
		
		return reqStr;	
	}
	public Map send(Map<String, String> reqMap, String url, String key) {
		String respStr = request(url, key, reqMap);
		Map respMap = getResp(key, respStr);
		return respMap;
	}
	
	public static void main(String[] args) {
		System.out.println("MD5:"+Demo.MD5Encode("123456abcdef汉字"));
		Map<String, String> reqMap = new HashMap<String, String>();
		reqMap = getPayMap(reqMap);
		Map respStr = new Demo_V2().send(reqMap, "https://pay.echase.cn/jygatewayPn/api", "Excel中提供秘钥");
		System.out.println("返回map：" + respStr);
	}
	
	public static Map<String,String> getPayMap(Map<String,String> map){
		map.put("mch_id","Excel中提供商户号");
		map.put("body","微信显示商品名称");
		map.put("detail","中文");
		map.put("mch_create_ip","真是的外网IP");
		map.put("total_fee","1");
		map.put("service","create");
		map.put("notify_url","http");
		map.put("callback_url","http://www.baidu.com");
		map.put("scene_info", "相同格式的场景信息");
		map.put("out_trade_no",UUID.randomUUID().toString().replace("-", ""));
		map.put("nonce_str","0001");
		map.put("trade_type","pay.weixin.h5");
		return map;
	}
}
