<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// request parameters //////////////////////////////////////
		
		// To receive the parameter
		request.setCharacterEncoding("UTF-8");
		String merchant_code = request.getParameter("merchant_code");	
		String service_type = request.getParameter("service_type");	
		String interface_version = request.getParameter("interface_version");		
		String sign_type = request.getParameter("sign_type");				
		String order_no = request.getParameter("order_no");
		String trade_no = request.getParameter("trade_no");		

		/** Data signature
		The definition of signature rule is as follows : 
		（1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed; 
		（2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, the composition rule is as follows : 
		Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N 
		*/


		StringBuffer signSrc= new StringBuffer();			
		signSrc.append("interface_version=").append(interface_version).append("&");
		signSrc.append("merchant_code=").append(merchant_code).append("&");
		signSrc.append("order_no=").append(order_no).append("&");			
		signSrc.append("service_type=").append(service_type);			
		
		if (!"".equals(trade_no)) {
			signSrc.append("&trade_no=").append(trade_no);	
		}
		
			
		String signInfo = signSrc.toString();
		String sign = "" ;
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
		
		if("RSA".equals(sign_type)){//for  sign_type = "RSA"
			
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
  <form name="dinpayForm" method="post" action="https://query.xhbill.com/query" >
	<input type="hidden" name="sign" value="<%=sign%>" />
	<input type="hidden" name="merchant_code" value="<%=merchant_code%>" />
	<input type="hidden" name="service_type" value="<%=service_type%>" />		
	<input type="hidden" name="interface_version" value="<%=interface_version%>" />		
	<input type="hidden" name="sign_type" value="<%=sign_type%>" />		
	<input type="hidden" name="order_no" value="<%=order_no%>"/>
	<input type="hidden" name="trade_no" value="<%=trade_no%>" />	
  </form>
</body>
</html>
