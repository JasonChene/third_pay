package util;

import java.io.IOException;
import java.util.Map;

import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.methods.PostMethod;

public class EncodeUtil {
	/****
	 * 将字符串转换为加密的串
	 * @param transMap
	 * @return
	 */
	public  static String getUrlStr(Map<String,String> transMap){
		//组织需要加密的字符串
		String transStr="";
		int flag=0;
		for(String key:transMap.keySet()) 
		{
			if((transMap.size()-1)==flag){
				transStr=transStr+key+"="+transMap.get(key);
			}else{
				transStr=transStr+key+"="+transMap.get(key)+"&";
			}
			flag++;
		} 
		return 	transStr;
	}
	
	/***
	 * 
	 * @param url
	 * @param map
	 * @param charSet
	 * @return
	 */
	public static  String POSTReturnString(String url, Map<String, String> map,String charSet) {
		HttpClient   client = new HttpClient();
		PostMethod method = new PostMethod(url);
		method.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=" + charSet);
		for (Map.Entry<String, String> entry : map.entrySet()) {
			method.setParameter(entry.getKey(), entry.getValue());
		}
		try {
			int statusCode = client.executeMethod(method);
			if (statusCode != 200) {
				System.out.println("statusCode=" + statusCode);
				return null;
			} else {
				String resp = method.getResponseBodyAsString();
				System.out.println("resp=" + resp);
				return resp;
			}
		} catch (HttpException e) {
			e.printStackTrace();
			return "" + e.getMessage();
		} catch (IOException e) {
			e.printStackTrace();
			return "" + e.getMessage();
		}
	}
	
	public static String requestBody(String url,String merId,String transData){
		HttpClient   client = new HttpClient();
		PostMethod  method= new PostMethod(url);
		method.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=gbk");
		method.setParameter("merId",merId);
		method.setParameter("transData",transData);
		client.setConnectionTimeout(8000);
		try {
			int statusCode = client.executeMethod(method);
			if (statusCode != 200) {
				System.out.println("statusCode=" + statusCode);
				return null;
		    }else{
		    	String resp = method.getResponseBodyAsString();
		    	System.out.println("resp="+resp);
		    	return resp;
		    }
		} catch (HttpException e){
			e.printStackTrace();
			return ""+e.getMessage();
		} catch (IOException e) {
			e.printStackTrace();
			return ""+e.getMessage();
		}
	}
}
