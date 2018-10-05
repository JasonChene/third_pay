package com.weixin.utils;

import java.io.UnsupportedEncodingException;
import java.security.NoSuchAlgorithmException;
import java.util.Map;

public class Utility {
	public static String CreateSign(Map<String, String> prams) {
        StringBuilder sb = new StringBuilder();
        for (Map.Entry<String, String> entry : prams.entrySet()) {
			if("" == entry.getValue() || null == entry.getValue()) {
				continue;
			}
			sb.append(entry.getKey()+"="+entry.getValue()+"&");
		}
        if(sb.length() > 0) {
        	sb.deleteCharAt(sb.length()-1);//删除字符串中的最后一个，也就是最后一个&
        }
        return sb.toString();
    }

    public static String CreateSignValue(Map<String, String> prams)
    {
    	StringBuilder sb = new StringBuilder();
        for (Map.Entry<String, String> entry : prams.entrySet()) {
			if("" == entry.getValue() || null == entry.getValue() || entry.getKey() == "sign") {
				continue;
			}
			sb.append(""+entry.getKey()+"="+entry.getValue()+"&");
			if(sb.length() > 0) {
				sb.deleteCharAt(sb.length()-1);//删除字符串中的最后一个，也就是最后一个&
			}
		}
        System.out.println("sb.toString()1"+sb.toString());
        return sb.toString();
    }

    public static String Md5Encrypt(String strToBeEncrypt) throws UnsupportedEncodingException, NoSuchAlgorithmException {
    	String retStr = Md5.md5Hex(strToBeEncrypt);
        return retStr;
    }
    
}
