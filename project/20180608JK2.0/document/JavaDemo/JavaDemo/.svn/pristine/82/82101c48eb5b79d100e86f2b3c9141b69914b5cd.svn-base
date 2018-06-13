/***
 * 
 * 发起交易查询的类
 * 
 * 
 */

package com.smartpay.action;

import java.util.Date;
import java.util.HashMap;
import java.util.Map;

import org.apache.http.HttpEntity;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;

import com.smartpay.util.Configuration;
import com.smartpay.util.MD5;
import com.smartpay.util.SignUtils;

import net.sf.json.JSONObject;

public class PayQueryAction {

	public static void main(String[] args) throws Exception{
		
		Map<String, String>  params = new HashMap<String, String>();
		
		
		
		params.put("version", "1.0");
		params.put("charset", "UTF-8");  
		params.put("sign_type", "MD5");   //目前仅支持md5签名的方式
		params.put("merchant_id", Configuration.merchantid);  //根据平台分配的merchantid, 替换配置
		params.put("out_trade_no", "abcdeegg11");   //商家系统的订单号， 请保持再商家系统里面唯一
		params.put("nonce_str", String.valueOf(new Date().getTime()));

		
        StringBuilder buf = new StringBuilder((params.size() +1) * 10);
        SignUtils.buildPayParams(buf,params,false);
        String preStr = buf.toString();
        String sign = MD5.getMD5(preStr+"&key=" + Configuration.key);
        params.put("sign", sign);
        StringBuilder newbuf = new StringBuilder((params.size() +1) * 10);
        SignUtils.buildPayParams(newbuf,params,false);
        String newStr = newbuf.toString();
        System.out.println(newStr);
		HttpPost httpPost = new HttpPost(Configuration.queryURL);
		StringEntity entity = new StringEntity(newStr,"utf-8");//解决中文乱码问题    
		httpPost.setEntity(entity);
		httpPost.addHeader(HTTP.CONTENT_TYPE, "application/x-www-form-urlencoded");
		CloseableHttpClient httpClient = HttpClients.createDefault();
		CloseableHttpResponse response = httpClient.execute(httpPost);
		System.out.println(response.getStatusLine().getStatusCode() + "\n");
		HttpEntity result = response.getEntity();
		String responseContent = EntityUtils.toString(result, "UTF-8"); 
		System.out.println(responseContent);
		JSONObject resultObj = JSONObject.fromObject(responseContent);
		
		if(resultObj.getString("status").equalsIgnoreCase("0")) {  //标识成功
			
			Map<String, String> respMap  = resultObj;
			if(SignUtils.checkParam(respMap, Configuration.key)) {
				
				if(resultObj.getString("result_code").equalsIgnoreCase("0")) {  //业务成功
					
					
					/**
					 * 获取订单的状态
					 * 
					 * 1表示成功
					 */
					String tradeState = resultObj.getString("trade_state");
					
					System.out.println(tradeState);
					
				}else {
					
						//失败
					
				}
				
				
			}else {
				// 签名失败
				
				
			}
			
			
		}else {
			
			
			//异常
			
		}
		
		
		
		
		
		response.close();
		httpClient.close();
		
		
		
	}
	
}
