<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 请求参数 request parameters //////////////////////////////////////
		
		// 接收表单提交参数
		request.setCharacterEncoding("UTF-8");			
		String merchant_code = request.getParameter("merchant_code");	
		String service_type = request.getParameter("service_type");
		String notify_url = request.getParameter("notify_url");			
		String interface_version = request.getParameter("interface_version");				
		String input_charset = request.getParameter("input_charset");						
		String sign_type = request.getParameter("sign_type");
		String return_url = request.getParameter("return_url");
		String client_ip = request.getParameter("client_ip");			
		String order_no = request.getParameter("order_no");		
		String order_time = request.getParameter("order_time");		
		String order_amount = request.getParameter("order_amount");		
		String product_name = request.getParameter("product_name");		
		String product_num = request.getParameter("product_num");			
		String auth_code = request.getParameter("auth_code");		
		String redo_flag = request.getParameter("redo_flag");		

		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n		*/
		
		StringBuffer signSrc= new StringBuffer();	
		signSrc.append("auth_code=").append(auth_code).append("&");
		signSrc.append("client_ip=").append(client_ip).append("&");
		signSrc.append("input_charset=").append(input_charset).append("&");			
		signSrc.append("interface_version=").append(interface_version).append("&");
		signSrc.append("merchant_code=").append(merchant_code).append("&");
		signSrc.append("notify_url=").append(notify_url).append("&");					
		signSrc.append("order_amount=").append(order_amount).append("&");
		signSrc.append("order_no=").append(order_no).append("&");		
		signSrc.append("order_time=").append(order_time).append("&");			
		signSrc.append("product_name=").append(product_name).append("&");
		if (null != product_num && !"".equals(product_num)) {
			signSrc.append("product_num=").append(product_num).append("&");	
		}
		if (null != redo_flag && !"".equals(redo_flag)) {
			signSrc.append("redo_flag=").append(redo_flag).append("&");	
		}
		if (null != return_url && !"".equals(return_url)) {
			signSrc.append("return_url=").append(return_url).append("&");	
		}		
		signSrc.append("service_type=").append(service_type);
		
			
		String signInfo = signSrc.toString();
		String sign = "" ;
		if("RSA-S".equals(sign_type)){ //sign_type = "RSA-S"
			
			/** 
			1)merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作并获取商户私钥；获取商户私钥的同时，也要获取商户公钥（merchant_public_key）；
			调试运行代码之前首先先将商户公钥上传到商家后台"公钥管理"（如何获取和上传请查看《密钥对获取工具说明》），不上传商户公钥会导致调试运行代码时报错。
  			2)demo提供的merchant_private_key是测试商户号1111110166的商户私钥，请自行获取商户私钥并且替换	*/	
  			
  			String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";	
			sign = RSAWithSoftware.signByPrivateKey(signInfo, merchant_private_key);  // 商家签名（签名后报文发往dinpay）  
			System.out.println("RSA-S商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
			System.out.println("RSA-S商家发送的签名：" + sign.length() + " -->" + sign + "\n");
		}
		
		if("RSA".equals(sign_type)){ //数字证书加密方式 sign_type = "RSA"
		
			//请在商家后台证书下载处申请和下载pfx数字证书，一般要1~3个工作日才能获取到,1111110166.pfx是测试商户号1111110166的数字证书
			String webRootPath = request.getSession().getServletContext().getRealPath("/");
			String merPfxPath = webRootPath + "pfx/1111110166.pfx"; // 商家的pfx证书文件路径
			String pfxPass = "87654321";			  				// 商家的pfx证书密码,初始密码是商户号
			RSAWithHardware mh = new RSAWithHardware();						
			mh.initSigner(merPfxPath, pfxPass);		  
			sign = mh.signByPriKey(signInfo);		  				// 商家签名（签名后报文发往dinpay）
			System.out.println("RSA商家的pfx证书文件路径：" + merPfxPath.length() + " -->" + merPfxPath);
			System.out.println("RSA商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
			System.out.println("RSA商家发送的签名：" + sign.length() + " -->" + sign + "\n");
		}
		 
%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
</head>
<body onLoad="document.dinpayForm.submit();">
  <form name="dinpayForm" method="post" action="https://api.yuanruic.com/gateway/api/micropay" >
	<input type="hidden" name="sign" value="<%=sign%>" />
	<input type="hidden" name="merchant_code" value="<%=merchant_code%>" />	
	<input type="hidden" name="service_type" value="<%=service_type%>" />
	<input type="hidden" name="notify_url" value="<%=notify_url%>"/>	
	<input type="hidden" name="interface_version" value="<%=interface_version%>" />			
	<input type="hidden" name="input_charset" value="<%=input_charset%>" />	
	<input type="hidden" name="sign_type" value="<%=sign_type%>" />	
	<input type="hidden" name="return_url" value="<%=return_url%>"/>
	<input type="hidden" name="client_ip" value="<%=client_ip%>" />		
	<input type="hidden" name="order_no" value="<%=order_no%>"/>
	<input type="hidden" name="order_time" value="<%=order_time%>" />	
	<input type="hidden" name="order_amount" value="<%=order_amount%>"/>
	<input type="hidden" name="product_name" value="<%=product_name%>" />	
	<input type="hidden" name="product_num" value="<%=product_num%>" />		
	<input type="hidden" name="auth_code" value="<%=auth_code%>" />	
	<input type="hidden" name="redo_flag" value="<%=redo_flag%>"/>
  </form>
</body>
</html>
