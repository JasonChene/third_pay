package cn.zf.servlet;

import cn.zf.http.*;
import java.util.*;
import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.itrus.util.sign.RSAWithHardware;
import com.itrus.util.sign.RSAWithSoftware;

public class QueryOrdsServlet extends HttpServlet {

	public void service(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {	
		
		req.setCharacterEncoding("utf-8");
		res.setContentType("text/html;charset=utf-8");
		
		// Query requests address
		String reqUrl = "https://query.zfbill.net/query";
		
		// Query requests to return the result
		String result = null;
		
		// Get request paramenter
		String service_type = (String) req.getParameter("service_type");
		String merchant_code = (String) req.getParameter("merchant_code");
		String interface_version = (String) req.getParameter("interface_version");
		String sign_type = (String) req.getParameter("sign_type");		
		String order_no = (String) req.getParameter("order_no");
		String trade_no = (String) req.getParameter("trade_no");
		
		Map<String, String> reqMap = new HashMap<String, String>();
		reqMap.put("service_type", service_type);
		reqMap.put("merchant_code", merchant_code);
		reqMap.put("interface_version", interface_version);
		reqMap.put("sign_type", sign_type);
		reqMap.put("order_no", order_no);
		if (null != trade_no && !"".equals(trade_no)) {
			reqMap.put("trade_no", trade_no);	
		}
		
		/** Data signature
		The definition of signature rule is as follows : 
		£¨1£© In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn¡¯t need to be signed; 
		£¨2£© The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, the composition rule is as follows : 
		Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N		*/	
		
		StringBuffer signSrc= new StringBuffer();			
		signSrc.append("interface_version=").append(interface_version).append("&");
		signSrc.append("merchant_code=").append(merchant_code).append("&");
		signSrc.append("order_no=").append(order_no).append("&");			
		signSrc.append("service_type=").append(service_type);			
		if (null != trade_no && !"".equals(trade_no)) {
			signSrc.append("&trade_no=").append(trade_no);	
		}		
		
		String signInfo = signSrc.toString();
		String sign = "" ;
		if("RSA-S".equals(sign_type)){ // sign_type = "RSA-S"			
			
			/**
			1)merchant_private_key,get it from the tools for getting keys,please refer to the file call <how to get the keys>
	 	    2)you also need to get the merchant_public_key and upload it on QuickPay mechant system,also refer to <how to get the keys>
 			3)this merchant_private_key value is for mechant ID 1111110166	*/
			String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";	
			try {
				sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key);
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}	// Signture	signInfo:Signature parameters sorting			
			reqMap.put("sign", sign);				
			result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");		 	// Post request							
		}
		
		if("RSA".equals(sign_type)){ // sign_type = "RSA"
			
			//Get the pfx cetification on QuickPay mechant system,"Payment Management"->"Download Cetification",1111110166.pfx is for merchant ID 1111110166	
			String webRootPath = req.getSession().getServletContext().getRealPath("/");
			String merPfxPath = webRootPath + "pfx/1111110166.pfx"; 				// The certificate path
			String merPfxPass = "87654321";			  								// The certificate password, value is your merchant ID
			RSAWithHardware mh = new RSAWithHardware();						
			try {
				mh.initSigner(merPfxPath, merPfxPass);
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}	  
			sign = mh.signByPriKey(signInfo);		  								// Signture	signInfo:Signature parameters sorting
			reqMap.put("sign", sign);				
			result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");			// Post request		
		}
		
		System.out.println("signInfo value£º" + signInfo.length() + " -->" + signInfo);
		System.out.println("sign value£º" + sign.length() + " --> " + sign);
		System.out.println("result value£º"+result);
        System.out.println("---------------------------------------------------------------------------------------------------------------------------------------------");  
  
		PrintWriter pw = res.getWriter();
		pw.write(result);															// Returns the result data to the requested page
        pw.flush();
		pw.close();		
	}
}
