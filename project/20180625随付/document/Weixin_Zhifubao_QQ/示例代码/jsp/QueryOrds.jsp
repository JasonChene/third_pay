<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="cn.zf.http.*" %>
<%@ page import="java.io.*" %>
<%@ page import="java.util.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 请求参数  ////////////////////////////////////////////////
			 
		// 查询请求地址
		String reqUrl = "https://query.suifupay.com/query";
		
		// 查询返回结果 
		String result = null;
		
		// 接收表单提交参数
		String service_type = (String) request.getParameter("service_type");
		String merchant_code = (String) request.getParameter("merchant_code");
		String interface_version = (String) request.getParameter("interface_version");
		String sign_type = (String) request.getParameter("sign_type");		
		String order_no = (String) request.getParameter("order_no");
		String trade_no = (String) request.getParameter("trade_no");
		
		Map<String, String> reqMap = new HashMap<String, String>();
		reqMap.put("service_type", service_type);
		reqMap.put("merchant_code", merchant_code);
		reqMap.put("interface_version", interface_version);
		reqMap.put("sign_type", sign_type);
		reqMap.put("order_no", order_no);
		if (null != trade_no && !"".equals(trade_no)) {
			reqMap.put("trade_no", trade_no);	
		}
		
		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名参数排序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n		*/
		
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
			1)merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作并获取商户私钥；获取商户私钥的同时，也要获取商户公钥（merchant_public_key）；调试运行
			代码之前首先先将商户公钥上传到商家后台"支付管理"->"公钥管理"（如何获取和上传请查看《密钥对获取工具说明》），不上传商户公钥会导致调试运行代码时报错。
			2)demo提供的merchant_private_key是测试商户号100100101102的商户私钥，请自行获取商户私钥并且替换	*/	
			
			String merchant_private_key ="MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAOlji/yfZS3DKefgXJandiPPlheoJeOPgiqdy7WO5kYDxpYIbcmJXp1sIxC0MqY5pyWIOyBlW/NHWDfRyTIYvFm6vxx9m/TeTYKRXHPNdCefXNeV5XoCB7s7gJoeqR1qwgBE0a2Sw4slMrKL4LSv0J0o3po9SgYhP06m3az9dZzDAgMBAAECgYEAgLGdvX1/Y06cyvnS1FgfMvwCvaTAz8yQcEN20vSrUaw/Uvfu4hTXkWLRqyJSU4qy+mU2hVSe8XVD1fPeR7uQD7TWqmXCaIC12DqWFvA2Eio/x4nIixj5t4PN4lbUVBHH/OnHypRWFNcQemR4OWcYCWi5/cYshEaOk9XQnUQKvcECQQD7ZBDA+d8IXM7jtlNNEp+ySw87iz8pKhBoVt3XrAkGczuM9A+ZY+ReViHVxuxqd5AgxSPj1hoQeFR0BnPEVKgzAkEA7ar8kvzp9sPMDujN9QSr+yq73CTlRr8W5q0vMCxsJagie6znvtsAzka3EkI0Srix5fHjRWrWWknblDat5CnpMQJAGfYLhOJJFiP/eaSqlZsGwvLdb9jBfQ7LAvt1jYBGrlPYIoZR1hVq0BM1C06vu479Y2T4f97scib7EcBY1D2p4QJALvdmOEngllI1BhE9ehj1P4o/W46y/FsQ1H8O3y84I+zo+5W8qjuvGhRhYytQGCANj8pQRkyJlYgY614u2PLrUQJAAv9neGYMeFdZnanlsdg6EepWAzvT2tv45WF74BQKJzZF+IFtE1XD85QcqkOJ3o8WgVwXK6/BCn5vNahImz+Ohg==";
			sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key);	// 签名   signInfo签名参数排序，  merchant_private_key商户私钥  				
			reqMap.put("sign", sign);				
			result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");		 	// 向发送POST请求							
		}
		
		if("RSA".equals(sign_type)){ // 数字证书加密方式 sign_type = "RSA"
			
			// 请在商家后台"支付管理"->"证书下载"处申请和下载pfx数字证书，一般要1~3个工作日才能获取到，100100101102.pfx是测试商户号100100101102的数字证书
			String webRootPath = request.getSession().getServletContext().getRealPath("/");
			String merPfxPath = webRootPath + "pfx/100100101102.pfx"; 				// 商家的pfx证书文件路径
			String merPfxPass = "87654321";			  								// 商家的pfx证书密码,初始密码是商户号
			RSAWithHardware mh = new RSAWithHardware();						
			mh.initSigner(merPfxPath, merPfxPass);	  
			sign = mh.signByPriKey(signInfo);		  								// 签名   signInfo签名参数排序
			reqMap.put("sign", sign);				
			result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");			// 向发送POST请求	
		}
		
		System.out.println("签名参数排序：" + signInfo.length() + " --> " + signInfo);
		System.out.println("sign值：" + sign.length() + " --> " + sign);
		System.out.println("result值："+result);
        System.out.println("---------------------------------------------------------------------------------------------------------------------------------------------");  
  
		PrintWriter pw = response.getWriter();
		pw.write(result);															// 返回result数据给请求页面
        pw.flush();
		pw.close();	
		 
%>
