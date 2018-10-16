package com.cn.hunst.util;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.commons.httpclient.Header;
import org.apache.commons.httpclient.HttpClient;
import org.apache.commons.httpclient.HttpException;
import org.apache.commons.httpclient.HttpStatus;
import org.apache.commons.httpclient.NameValuePair;
import org.apache.commons.httpclient.methods.PostMethod;
import org.apache.commons.lang3.ArrayUtils;


public class HttpUtil {
	
	@SuppressWarnings("unchecked")
	public  static String methodPost(String url,Map<String,String> data,String charset){  
        String response= "";
        HttpClient httpClient = new HttpClient();
        PostMethod postMethod = new PostMethod(url);  
        postMethod.getParams().setContentCharset(charset);
        NameValuePair[] pairs = new NameValuePair[data.keySet().size()];
        int i = 0 ;
        for(Entry<String, String> entry : data.entrySet()){
        	NameValuePair pair = new NameValuePair();
        	pair.setName(entry.getKey());
        	pair.setValue(entry.getValue());
        	pairs[i] = pair;
        	i++;
        }
        postMethod.setRequestBody(pairs);  
        int statusCode = 0;  
        try {  
            statusCode = httpClient.executeMethod(postMethod);  
        } catch (HttpException e) {  
            e.printStackTrace();  
        } catch (IOException e) {  
            e.printStackTrace();  
        }  
        if (statusCode == HttpStatus.SC_MOVED_PERMANENTLY  
                || statusCode == HttpStatus.SC_MOVED_TEMPORARILY) {  
            Header locationHeader = postMethod.getResponseHeader("location");  
            String location = null;  
            if (locationHeader != null) {  
                location = locationHeader.getValue();  
                System.out.println("The page was redirected to:" + location);  
                response= methodPost(location,data, charset);
            } else {  
                System.err.println("Location field value is null.");  
            }  
        } else {  
            System.out.println(postMethod.getStatusLine());  
  
            try {  
                response= postMethod.getResponseBodyAsString();  
            } catch (IOException e) {  
                e.printStackTrace();  
            }  
            postMethod.releaseConnection();  
        }  
        return response;  
    }  
       
}
