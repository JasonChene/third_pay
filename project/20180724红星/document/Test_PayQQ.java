package com.cn;
import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;



import com.cn.CipherUtil;

public class Test_PayQQ {
	/**
	 * @param args
	 * @throws Exception 
	 */
	public static void main(String[] args) throws Exception {
		String key = "123456789";
		SimpleDateFormat d = new SimpleDateFormat("yyyyMMddHHmmss");
		Date date = new Date();
		Test_PayQQ service = new Test_PayQQ();
		Map reqParam = new HashMap();
		
		String url = "http://192.168.12.15:18000/GW/gw.inter";
		Map<String, String> map = new HashMap<String, String>();
		map.put("cmd", "PAYQQ");//命令字
		map.put("version", "2.0");//版本号
		map.put("appid", "mx_test");//商户id
		map.put("ordertime", d.format(new Date()));
		map.put("userid","CUST_123456789");
		map.put("apporderid","APPORDERID"+System.currentTimeMillis());
		map.put("orderbody","QQ支付抬头");
		map.put("amount","2");
		map.put("notifyurl","http://QQ/notify");
		
		Map<String, String> resultMap = service.invoke(map, url, key);
     	System.out.println(resultMap);
	}

	/**
	 * 各个接口的方法调用
	 * 
	 * @param paramMap
	 *            请求参数(不含hmac)
	 * @param url
	 *            请求地址
	 * @param appInitKey
	 *            钱包分配给商家的密钥
	 * @return
	 */
	public Map<String, String> invoke(Map<String, String> paramMap, String url, String appInitKey) {
		Map<String, String> resultMap = new HashMap<String, String>();
		String params = createParam(paramMap, appInitKey);
		String result = submitPost(url, params);
		if (result == null) {
			return null;
		}
		System.out.println("返回："+result);
		System.out.println("---------");
		String[] split = result.split("&");
		for (int i = 0; i < split.length; i++) {
			String[] temp = split[i].split("=");
			if (temp.length == 1) {
				resultMap.put(temp[0], "");
			}
			if (temp.length > 1) {
				resultMap.put(temp[0], temp[1]);
			}
		}
		return resultMap;
	}

	/**
	 * 组合请求参数(a=b&c=d的形式)
	 * 
	 * @param map
	 *            请求参数
	 * @param appInitKey
	 *            钱包分配给商家的密钥
	 * @return
	 */
	public String createParam(Map<String, String> map, String appInitKey) {
		try {
			if (map == null || map.isEmpty()) {
				return null;
			}

			//对参数名按照ASCII升序排序
			Object[] key = map.keySet().toArray();
			Arrays.sort(key);

			//生成加密原串  
			StringBuffer res = new StringBuffer(128);
			for (int i = 0; i < key.length; i++) {
				res.append(key[i] + "=" + map.get(key[i]) + "&");
			}

			String rStr = res.substring(0, res.length() - 1);
			System.out.println("请求接口加密原串 = " + rStr);
			if (appInitKey == null) {
				return rStr + "&hmac=" + getKeyedDigest(rStr, "");
			}

			return rStr + "&hmac=" + getKeyedDigest(rStr, appInitKey);
		} catch (Exception e) {
			e.printStackTrace();
		}

		return null;
	}

	/**
	 * 加密方法
	 * 
	 * @param strSrc
	 *            加密原串
	 * @param key
	 *            加密密钥
	 * @return
	 */
	public String getKeyedDigest(String strSrc, String key) {
		try {
			MessageDigest md5 = MessageDigest.getInstance("MD5");
			md5.update(strSrc.getBytes("GBK"));

			String result = "";
			byte[] temp;
			temp = md5.digest(key.getBytes("GBK"));
			for (int i = 0; i < temp.length; i++) {
				result += Integer.toHexString((0x000000ff & temp[i]) | 0xffffff00).substring(6);
			}
			return result;
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		}

		return null;
	}

	/**
	 * POST方法的提交方式
	 * 
	 * @param url
	 *            请求地址
	 * @param params
	 *            请求参数
	 * @return
	 */
	public String submitPost(String url, String params) {
		StringBuffer responseMessage = null;
		java.net.HttpURLConnection connection = null;
		java.net.URL reqUrl = null;
		OutputStreamWriter reqOut = null;
		InputStream in = null;
		BufferedReader br = null;
		int charCount = -1;
		try {
			responseMessage = new StringBuffer(128);
			reqUrl = new java.net.URL(url);
			connection = (java.net.HttpURLConnection) reqUrl.openConnection();
			connection.setReadTimeout(50000);
			connection.setConnectTimeout(100000);
			connection.setDoOutput(true);
			connection.setDoInput(true);
			connection.setRequestMethod("POST");
			reqOut = new OutputStreamWriter(connection.getOutputStream());
			reqOut.write(params);
			reqOut.flush();

			in = connection.getInputStream();
			br = new BufferedReader(new InputStreamReader(in, "GBK"));
			while ((charCount = br.read()) != -1) {
				responseMessage.append((char) charCount);
			}
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			try {
				if (in != null) {
					in.close();
				}
				if (reqOut != null) {
					reqOut.close();
				}
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

		return responseMessage.toString();
	}
	
}
