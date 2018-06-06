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

public class H5APIPayServlet extends HttpServlet {

	public void service(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {	
		
		req.setCharacterEncoding("utf-8");
		res.setContentType("text/html;charset=utf-8");
		
		// 支付请求地址
		String reqUrl = "https://api.zfbill.net/gateway/api/h5apipay";
		
		// 支付请求返回结果
		String result = null;
		
		// 接收表单提交参数
		String merchant_code = (String) req.getParameter("merchant_code");
		String service_type = (String) req.getParameter("service_type");
		String notify_url = (String) req.getParameter("notify_url");		
		String interface_version = (String) req.getParameter("interface_version");
		String client_ip = (String) req.getParameter("client_ip");
		String sign_type = (String) req.getParameter("sign_type");		
		String order_no = (String) req.getParameter("order_no");
		String order_time = (String) req.getParameter("order_time");
		String order_amount = (String) req.getParameter("order_amount");
		String product_name = (String) req.getParameter("product_name");
		
		Map<String, String> reqMap = new HashMap<String, String>();
		reqMap.put("merchant_code", merchant_code);
		reqMap.put("service_type", service_type);
		reqMap.put("notify_url", notify_url);
		reqMap.put("interface_version", interface_version);
		reqMap.put("client_ip", client_ip);
		reqMap.put("sign_type", sign_type);
		reqMap.put("order_no", order_no);
		reqMap.put("order_time", order_time);
		reqMap.put("order_amount", order_amount);
		reqMap.put("product_name", product_name);
		
		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名参数排序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n		*/
		
		StringBuffer signSrc= new StringBuffer();
		signSrc.append("client_ip=").append(client_ip).append("&");	
		signSrc.append("interface_version=").append(interface_version).append("&");
		signSrc.append("merchant_code=").append(merchant_code).append("&");				
		signSrc.append("notify_url=").append(notify_url).append("&");	
		signSrc.append("order_amount=").append(order_amount).append("&");
		signSrc.append("order_no=").append(order_no).append("&");
		signSrc.append("order_time=").append(order_time).append("&");
		signSrc.append("product_name=").append(product_name).append("&");
		signSrc.append("service_type=").append(service_type);		
				
		String signInfo = signSrc.toString();
		String sign = "" ;
		if("RSA-S".equals(sign_type)){ // sign_type = "RSA-S"			
			
			/**
			1)merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作并获取商户私钥；获取商户私钥的同时，也要获取商户公钥（merchant_public_key）；调试运行
			代码之前首先先将商户公钥上传到速汇宝商家后台"支付管理"->"公钥管理"（如何获取和上传请查看《密钥对获取工具说明》），不上传商户公钥会导致调试运行代码时报错。
			2)demo提供的merchant_private_key是测试商户号1111110166的商户私钥，请自行获取商户私钥并且替换	*/	
			
			String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";	
			try {
				sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key);	// 签名   signInfo签名参数排序，  merchant_private_key商户私钥  				
				reqMap.put("sign", sign);				
				result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");		 	// 向速汇宝发送POST请求				
			} catch (Exception e) {
				e.printStackTrace();
			}			
		}
		
		if("RSA".equals(sign_type)){ // 数字证书加密方式 sign_type = "RSA"
			
			// 请在商家后台"支付管理"->"证书下载"处申请和下载pfx数字证书，一般要1~3个工作日才能获取到，1111110166.pfx是测试商户号1111110166的数字证书
			String webRootPath = req.getSession().getServletContext().getRealPath("/");
			String merPfxPath = webRootPath + "pfx/1111110166.pfx"; 					// 商家的pfx证书文件路径
			String merPfxPass = "87654321";			  									// 商家的pfx证书密码,初始密码是商户号
			RSAWithHardware mh = new RSAWithHardware();						
			try {
				mh.initSigner(merPfxPath, merPfxPass);
			} catch (Exception e) {
				e.printStackTrace();
			}	  
			sign = mh.signByPriKey(signInfo);		  									// 签名   signInfo签名参数排序
			reqMap.put("sign", sign);				
			result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");				// 向速汇宝发送POST请求
		}
		
		System.out.println("签名参数排序：" + signInfo.length() + " --> " + signInfo);
		System.out.println("sign值：" + sign.length() + " --> " + sign);
		System.out.println("result值："+result);
        System.out.println("---------------------------------------------------------------------------------------------------------------------------------------------");  
        
		PrintWriter pw = res.getWriter();
		pw.write(result);																// 返回result数据给请求页面
        pw.flush();
		pw.close();		
	}	
}
