<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
////////////////////////////////////  request parameters //////////////////////////////////////
		
		// To receive the parameter
		request.setCharacterEncoding("UTF-8");			
		String merchant_code = request.getParameter("merchant_code");	
		String service_type = request.getParameter("service_type");
		String interface_version = request.getParameter("interface_version");		
		String input_charset = request.getParameter("input_charset");				
		String notify_url = request.getParameter("notify_url");
		String sign_type = request.getParameter("sign_type");		
		String order_no = request.getParameter("order_no");		
		String order_time = request.getParameter("order_time");		
		String order_amount = request.getParameter("order_amount");		
		String product_name = request.getParameter("product_name");		
		String return_url = request.getParameter("return_url");
		String bank_code = request.getParameter("bank_code");		
		String redo_flag = request.getParameter("redo_flag");
		String product_code = request.getParameter("product_code");
		String product_num = request.getParameter("product_num");
		String product_desc = request.getParameter("product_desc");
		String pay_type = request.getParameter("pay_type");
		String client_ip = request.getParameter("client_ip");
		String extend_param = request.getParameter("extend_param");
		String extra_return_param = request.getParameter("extra_return_param");
		String show_url = request.getParameter("show_url");		

		
		/** Data signature
		The definition of signature rule is as follows : 
		（1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed; 
		（2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, the composition rule is as follows : 
		Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N 
		*/
	

		StringBuffer signSrc= new StringBuffer();	
		if (!"".equals(bank_code)) {
			signSrc.append("bank_code=").append(bank_code).append("&");	
		}
		if (!"".equals(client_ip)) {
			signSrc.append("client_ip=").append(client_ip).append("&");	
		}
		if (!"".equals(extend_param)) {
			signSrc.append("extend_param=").append(extend_param).append("&");	
		}
		if (!"".equals(extra_return_param)) {
			signSrc.append("extra_return_param=").append(extra_return_param).append("&");	
		}
		
		signSrc.append("input_charset=").append(input_charset).append("&");			
		signSrc.append("interface_version=").append(interface_version);
		signSrc.append("&merchant_code=").append(merchant_code);
		signSrc.append("&notify_url=").append(notify_url);					
		signSrc.append("&order_amount=").append(order_amount);
		signSrc.append("&order_no=").append(order_no);		
		signSrc.append("&order_time=").append(order_time);
		
		if (!"".equals(pay_type)) {
			signSrc.append("&pay_type=").append(pay_type);	
		}	
		if (!"".equals(product_code)) {
			signSrc.append("&product_code=").append(product_code);	
		}
		if (!"".equals(product_desc)) {
			signSrc.append("&product_desc=").append(product_desc);	
		}		
		signSrc.append("&product_name=").append(product_name);
		if (!"".equals(product_num)) {
			signSrc.append("&product_num=").append(product_num);	
		}	
		if (!"".equals(redo_flag)) {
			signSrc.append("&redo_flag=").append(redo_flag);	
		}
		if (!"".equals(return_url)) {
			signSrc.append("&return_url=").append(return_url);	
		}	
		
		signSrc.append("&service_type=").append(service_type);
			
		if (!"".equals(show_url)) {
			signSrc.append("&show_url=").append(show_url);	
		}
				
		
		String signInfo = signSrc.toString();
		
		String sign="";
		
		
		if("RSA-S".equals(sign_type)) {//for sign_type = "RSA-S"
			
		/*1)merchant_private_key,get it from the tools for getting keys,please refer to the file call <how to get the keys>
	 	  2)you also need to get the merchant_public_key and upload it on QuickPay mechant system,also refer to <how to get the keys>
	  	 */
	
		// this merchant_private_key is for mechant ID 1111110166
		 	String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALf/+xHa1fDTCsLYPJLHy80aWq3djuV1T34sEsjp7UpLmV9zmOVMYXsoFNKQIcEzei4QdaqnVknzmIl7n1oXmAgHaSUF3qHjCttscDZcTWyrbXKSNr8arHv8hGJrfNB/Ea/+oSTIY7H5cAtWg6VmoPCHvqjafW8/UP60PdqYewrtAgMBAAECgYEAofXhsyK0RKoPg9jA4NabLuuuu/IU8ScklMQIuO8oHsiStXFUOSnVeImcYofaHmzIdDmqyU9IZgnUz9eQOcYg3BotUdUPcGgoqAqDVtmftqjmldP6F6urFpXBazqBrrfJVIgLyNw4PGK6/EmdQxBEtqqgXppRv/ZVZzZPkwObEuECQQDenAam9eAuJYveHtAthkusutsVG5E3gJiXhRhoAqiSQC9mXLTgaWV7zJyA5zYPMvh6IviX/7H+Bqp14lT9wctFAkEA05ljSYShWTCFThtJxJ2d8zq6xCjBgETAdhiH85O/VrdKpwITV/6psByUKp42IdqMJwOaBgnnct8iDK/TAJLniQJABdo+RodyVGRCUB2pRXkhZjInbl+iKr5jxKAIKzveqLGtTViknL3IoD+Z4b2yayXg6H0g4gYj7NTKCH1h1KYSrQJBALbgbcg/YbeU0NF1kibk1ns9+ebJFpvGT9SBVRZ2TjsjBNkcWR2HEp8LxB6lSEGwActCOJ8Zdjh4kpQGbcWkMYkCQAXBTFiyyImO+sfCccVuDSsWS+9jrc5KadHGIvhfoRjIj2VuUKzJ+mXbmXuXnOYmsAefjnMCI6gGtaqkzl527tw=";		
			
			sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key) ;  
			
			//System.out.println("signInfo：" + signInfo.length() + " -->" + signInfo);
			//System.out.println("sign：" + sign.length() + " -->" + sign + "\n");
		
		}
		
		if("RSA".equals(sign_type)){//for sign_type = "RSA"
			String rootPath=this.getClass().getResource("/").toString();
			//get the pfx cetification on QuickPay mechant system,"Payment Management"->"Download Cetification",1111110166.pfx is for merchant ID 1111110166
			String path= rootPath.substring(rootPath.indexOf("/")+1,rootPath.length()-8)+"certification/1111110166.pfx";	
			String pfxPass = "87654321";   //cetification's pwd,pwd is your merchant ID 
			RSAWithHardware mh = new RSAWithHardware();						
			mh.initSigner(path, pfxPass);		  
			sign = mh.signByPriKey(signInfo);		
			
			//System.out.println("signInfo：" + signInfo.length() + " -->" + signInfo);
			//System.out.println("sign：" + sign.length() + " -->" + sign + "\n");
			}
		 
%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
</head>
<body onLoad="document.dinpayForm.submit();">
  <form name="dinpayForm" method="post" action="https://pay.xhbill.com/gateway?input_charset=<%=input_charset%>" >
	<input type="hidden" name="sign" value="<%=sign%>" />
	<input type="hidden" name="merchant_code" value="<%=merchant_code%>" />
	<input type="hidden" name="service_type" value="<%=service_type%>" />	
	<input type="hidden" name="interface_version" value="<%=interface_version%>" />			
	<input type="hidden" name="input_charset" value="<%=input_charset%>" />	
	<input type="hidden" name="notify_url" value="<%=notify_url%>"/>
	<input type="hidden" name="sign_type" value="<%=sign_type%>" />		
	<input type="hidden" name="order_no" value="<%=order_no%>"/>
	<input type="hidden" name="order_time" value="<%=order_time%>" />	
	<input type="hidden" name="order_amount" value="<%=order_amount%>"/>
	<input type="hidden" name="product_name" value="<%=product_name%>" />	
	<input type="hidden" name="return_url" value="<%=return_url%>"/>	
	<input type="hidden" name="bank_code" value="<%=bank_code%>" />	
	<input type="hidden" name="redo_flag" value="<%=redo_flag%>"/>
	<input type="hidden" name="product_code" value="<%=product_code%>"/>
	<input type="hidden" name="product_num" value="<%=product_num%>"/>
	<input type="hidden" name="product_desc" value="<%=product_desc%>"/>
	<input type="hidden" name="pay_type" value="<%=pay_type%>"/>
	<input type="hidden" name="client_ip" value="<%=client_ip%>"/>
	<input type="hidden" name="extend_param" value="<%=extend_param%>"/>
	<input type="hidden" name="extra_return_param" value="<%=extra_return_param%>"/>
	<input type="hidden" name="show_url" value="<%=show_url%>"/>
  </form>
</body>
</html>
