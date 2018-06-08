/**
 * 
 * 商户通知的类
 * 
 * */

package com.smartpay.action;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URLDecoder;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.smartpay.util.Configuration;
import com.smartpay.util.MD5;
import com.smartpay.util.SignUtils;

import net.sf.json.JSONObject;

public class NotifyCallbackAction extends HttpServlet {

	
	
 	private static final long serialVersionUID = 1L;

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        doPost(req, resp);
    }

    
    /**商家回调处理**/
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
    	
    	
    	BufferedReader br;  
		StringBuilder sb = null;  
		String reqBody = null;  
		br = new BufferedReader(new InputStreamReader(  
		        		req.getInputStream()));  
		String line = null;  
		sb = new StringBuilder();  
		while ((line = br.readLine()) != null) {  
		            sb.append(line);  
		}  
		reqBody = URLDecoder.decode(sb.toString(), "UTF-8"); 
		        
		System.out.println(reqBody);
		        
		JSONObject jsonObject = JSONObject.fromObject(reqBody);
    	Map prams = jsonObject;
    	
    	Map<String, String>  filterParams = SignUtils.paraFilter(prams);   //过滤掉空值
    	
    	//验证签名
        StringBuilder buf = new StringBuilder((filterParams.size() +1) * 10);
        SignUtils.buildPayParams(buf,filterParams,false);
        String preStr = buf.toString();
        String sign = MD5.getMD5(preStr+"&key=" + Configuration.key);
    	if(sign.equalsIgnoreCase(req.getParameter("sign"))) {    //如果签名正确
    		
    		/**
    		 * 判断订单是否处理过   
    		 * 如果已经处理过， 直接返回success给服务器
    		 */
    		
    		
    		
    		/**
    		 * 如果订单没有被处理 进行业务处理
    		 */
    		
    		
    		
    		/***
    		 * 返回给服务器success, 不然服务器会持续回调商家服务器
    		 */
    		resp.getWriter().println("success");
    		
    		
    		
    		
    		
    		
    	}else {
    		
    		
    		resp.getWriter().println("fail");
    		
    	}
    	resp.getWriter().flush();
		resp.getWriter().close(); 

    }
    
    
   
	
}
