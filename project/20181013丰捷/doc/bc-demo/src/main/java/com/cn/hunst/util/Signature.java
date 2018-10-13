package com.cn.hunst.util;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Map;
import java.util.Set;
import java.util.TreeMap;

import org.apache.commons.beanutils.BeanUtils;
import org.apache.commons.lang3.StringUtils;


public class Signature {
	
	
	/**
	 * 排序签名
	 * @param signMap
	 * @param key
	 * @return
	 */
	public static String sign(Map<String,String> signMap,String key){
	    Set<String> keyset = signMap.keySet();
	    ArrayList<String> list = new ArrayList<String>(keyset);
	    Collections.sort(list);
	
	    String encryptString = "";
	    for(int i = 0; i < list.size(); ++i) {
	    	String val = signMap.get(list.get(i));
	    	if(StringUtils.isNotEmpty(val)){
	    		encryptString = encryptString + list.get(i) + "=" + val +"&";
	    	}
	    }
	    encryptString = encryptString + "key="+key;
	    return MD5(encryptString);
	}
	
	/**
	 * 排序签名（Object�?
	 * @param obj
	 * @return String 加密后的字符�?
	 */
	public static String sign(Object obj, String key) {
		Map<String, String> signMap = new TreeMap<String,String>();
		try {
			signMap = BeanUtils.describe(obj);
		} catch (Exception e) {
			e.printStackTrace();
		}
		signMap.remove("v_sign");
		signMap.remove("class");
	    Set<String> keyset = signMap.keySet();
	    ArrayList<String> list = new ArrayList<String>(keyset);
	    Collections.sort(list);
	
	    String encryptString = "";
	    for(int i = 0; i < list.size(); ++i) {
	    	String val = signMap.get(list.get(i));
	    	if(StringUtils.isNotEmpty(val)){
	    		encryptString = encryptString + list.get(i) + "=" + val +"&";
	    	}
	    }
	    encryptString = encryptString+"key="+ key;
	    return MD5(encryptString);
	}
	
	/**
	 * MD5签名
	 * @param sourceStr 待签名字符串
	 * @return
	 */
	public static String MD5(String sourceStr) {
	    String result = "";
	    try {
	        MessageDigest e = MessageDigest.getInstance("MD5");
	        e.update(sourceStr.getBytes());
	        byte[] b = e.digest();
	        StringBuffer buf = new StringBuffer("");
	
	        for(int offset = 0; offset < b.length; ++offset) {
	            int i = b[offset];
	            if(i < 0) {
	                i += 256;
	            }
	            if(i < 16) {
	                buf.append("0");
	            }
	            buf.append(Integer.toHexString(i));
	        }
	        result = buf.toString();
	    } catch (NoSuchAlgorithmException var7) {
	        System.out.println(var7);
	    }
	    return result;
	}
	
	/**
	 * 验证签名(Map)
	 * @param signMap
	 * @param dsSecret
	 * @return
	 */
	public static boolean checkSign(Map<String, String> signMap, String dsSecret) {
		String sign = signMap.remove("sign");
		String localSign = sign(signMap, dsSecret);
		if(StringUtils.isEmpty(sign) || StringUtils.isEmpty(localSign)){
			return false;
		}
		if(sign.equals(localSign)){
			return true;
		}
		return false;
	}
	
	/**
	 * 验证签名(Obj)
	 * @param signMap
	 * @param dsSecret
	 * @return
	 */
	public static boolean checkSign(Object obj, String dsSecret,String key){
		String localSign  = sign(obj,key);
		if(StringUtils.isEmpty(dsSecret) || StringUtils.isEmpty(localSign)){
			return false;
		}
		if(dsSecret.equals(localSign)){
			return true;
		}
		return false;
	}
	
}
