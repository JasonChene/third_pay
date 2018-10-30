package com.hs.gate.yz;

import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.security.KeyFactory;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.security.Signature;
import java.security.spec.PKCS8EncodedKeySpec;
import java.security.spec.X509EncodedKeySpec;
import java.util.Map;
import java.util.SortedMap;
import java.util.TreeMap;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;


public class BankDaifu extends BasePay {
	public static final String MER_ID = "123";
	public static final String SIGN_KEY = "abce";
	public static final String PRIVATE_KEY = "";
	public static final String PUBLIC_KEY =  "";
	public static final String PrePayURL = "http:xxx";			
	
	public String daifuPay(DaifuBean bean) {
		try {
			SortedMap<String,String> postParamMap=new TreeMap<String, String>();
			
			postParamMap.put("memberid", MER_ID);
			postParamMap.put("orderid","");
			postParamMap.put("payAmount", "");
			postParamMap.put("cardHolder", "");
			postParamMap.put("notifyUrl", ");
			postParamMap.put("bankName", "");
			postParamMap.put("bankProvince", "");
			postParamMap.put("bankCity", "");
			postParamMap.put("bankCardNo", "");
			postParamMap.put("cardId", "");
			postParamMap.put("mobile", "");
			postParamMap.put("bankCode", "");
			postParamMap.put("bankBranchName", "");
			postParamMap.put("send_type", "D0");
			
			String localMd5 = mapToString(postParamMap,false) + "&key=" + SIGN_KEY;
			String md5sign = Encryptor.MD5Encode(localMd5, "UTF-8").toUpperCase();
			String sign = URLEncoder.encode(sign(md5sign), "UTF-8");
			
			String postString = mapToString(postParamMap,true) + "&sign=" + sign;
			TraceLog.debug(this, "代付提交数据："+ postString.toString());
			String res = doPostQueryCmd(PrePayURL, postString);

			if(null == res) {
				TraceLog.error(this, "代付服务器响应失败");
				return "error";
			} else {
				JSONObject jo = JSON.parseObject(res);
				String retCode = jo.getString("retCode");
				//4、解析返回参数
				if ("10000".equals(retCode)) {
					SortedMap<String,String> respMap = new TreeMap<String, String>();
					respMap.put("retCode", retCode);
					respMap.put("retMsg", jo.getString("retMsg"));
					respMap.put("merchantId", jo.getString("merchantId"));
					respMap.put("amount", jo.getString("amount"));
					respMap.put("payAmount", jo.getString("payAmount"));
					respMap.put("totalFee", jo.getString("totalFee"));
					respMap.put("orderId", jo.getString("orderId"));
					respMap.put("outOrderId", jo.getString("outOrderId"));
					respMap.put("status", jo.getString("status"));
					respMap.put("transTime", jo.getString("transTime"));
					
					String respmts = mapToString(respMap,false) + "&key=" + SIGN_KEY;
					String resMd5String = Encryptor.MD5Encode(respmts, "UTF-8").toUpperCase();
					String getsign = jo.getString("sign");
					//5、验签
					if (verify(resMd5String, getsign)) {
						TraceLog.debug(this, "渠道返回验签成功：" + jo.getString("outOrderId"));
					} else {
						TraceLog.error(this, "渠道返回验签错误：" + jo.getString("outOrderId"));
	
					}
					//6、结果处理
					String status = jo.getString("status");
					if ("3".equals(status)) {
						
					}else if("2".equals(status)){
						
					}else if("1".equals(status)){
						
					}
					
				}else if("9999".equals(retCode)){
					
				}else {
					TraceLog.error(this, "代付失败:");
					
				}
			}
		} catch (Exception e) {
			TraceLog.error(this, "Response failed:" + e.getMessage());
			return "failed";
		}
	}
	public static String sign(String data) {
		try {
			byte[] keyBytes = Base64.decode(PRIVATE_KEY);
			PKCS8EncodedKeySpec priPKCS8 = new PKCS8EncodedKeySpec(keyBytes);
			KeyFactory keyf = KeyFactory.getInstance("RSA");
			PrivateKey myprikey = keyf.generatePrivate(priPKCS8);
			Signature signet = Signature.getInstance("SHA1withRSA");
			signet.initSign(myprikey);
			byte[] infoByte = data.getBytes("UTF-8");
			signet.update(infoByte);
			byte[] signed = signet.sign();
			String sign = Base64.encode(signed);
			return sign;
		} catch (Exception e) {
			TraceLog.error("sign", "签名失败:" + e.getMessage());
			e.printStackTrace();
		}
		return null;
	}
	
	public static boolean verify(String data, String sign) {
		try {
	        byte[] keyBytes = Base64.decode(PUBLIC_KEY);
	        X509EncodedKeySpec keySpec = new X509EncodedKeySpec(keyBytes);
	        KeyFactory keyFactory = KeyFactory.getInstance("RSA");
	        PublicKey publicK = keyFactory.generatePublic(keySpec);
	        Signature signature = Signature.getInstance("SHA1withRSA");
	        signature.initVerify(publicK);
	        byte[] infoByte = data.getBytes("UTF-8");
	        signature.update(infoByte);
	        return signature.verify(Base64.decode(sign));
		} catch (Exception e) {
			TraceLog.error("verify", "验签失败:" + e.getMessage());
			e.printStackTrace();
		}
		return false;
    }
	
	public static String mapToString (Map<String, String> params, boolean uc){

		StringBuffer sb =new StringBuffer();
		String result ="";

		if (params == null || params.size() <= 0) {
			return "";
		}
		for (String key : params.keySet()) {
			String value = params.get(key);
			if (value == null || value.equals("")) {
				continue;
			}
			if(uc) {
				try {
					sb.append(key+"="+ URLEncoder.encode(value, "UTF-8")+"&");
				} catch (UnsupportedEncodingException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}else {
				sb.append(key+"="+ value +"&");
			}
		}

		result=sb.toString().substring(0,sb.length()-1);

		return result;
	}
	
	public static String doPostQueryCmd(String strURL, String req) {
		String result = null;
		BufferedReader in = null;
		BufferedOutputStream out = null;
		try {
			URL url = new URL(strURL);
			URLConnection con = url.openConnection();
			HttpURLConnection httpUrlConnection  =  (HttpURLConnection) con;
			httpUrlConnection.setRequestMethod("POST");
			con.setUseCaches(false);
			con.setDoInput(true);
			con.setDoOutput(true);
			out = new BufferedOutputStream(con.getOutputStream());
			byte outBuf[] = req.getBytes("utf-8");
			out.write(outBuf);
			out.close();

			in = new BufferedReader(new InputStreamReader(con.getInputStream(),"UTF-8"));
			StringBuffer sb = new StringBuffer();
			String data = null;

			while ((data = in.readLine()) != null) {
				sb.append(data);
			}
			TraceLog.debug("请求返回", "res："+ sb.toString());
			result = sb.toString();		
		} catch (Exception ex) {
			ex.printStackTrace();
		} finally {
			if (out != null) {
				try {
					out.close();
				} catch (IOException e) {
				}
			}
			if (in != null) {
				try {
					in.close();
				} catch (IOException e) {
				}
			}
		}
		if (result == null)
			return "";
		else
			return result;
	}
}
