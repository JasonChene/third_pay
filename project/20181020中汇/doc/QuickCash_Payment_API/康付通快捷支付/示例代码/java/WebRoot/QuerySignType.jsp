<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 请求参数 request parameters //////////////////////////////////////
		
		
	// merchant_private_key，商家私钥  
		String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALIgluJzrKhOw/+sKlUZW+GFISjeXCqNz45rhEd4pRhg92ZDwyJxsIWVMUggCJLjSAke2wmVOiYJB/V9rNwlCzal5BGCSD0y8VckUb8LMv5wnNxr3wjrXf6IbZWsgNOwZg1mo+Cji5LCwoKvYvbZNK33Nb9MwbBh1PHUVP8AsfM3AgMBAAECgYEAr6oyAtse39Dlu+OWz9u1X/+BhyNa82Bs20Au8KkK77LY6NJUw0gpVGOgeUeWDP31kYELdDTlZpMrdS9eZLBnj/QofFTx7GSeod+vV13cgA6rc0yzjTp25Dm7Xzihf15R5JiNIFzlSYC2TLz+HcJoprxY6Pf6I/1qBjZuoC67eEECQQDjDhEI7s010aXXYQy3xwC/RUDosnfMARqRCpYFCYmoyMiUZ7+ohIvWkkCcwHx7VNKnXfmF0ezdXNT2TCKfXj6hAkEAyNXFKkCPtbg+GFqUlxlfta1s7FJuC1b8ZyaA1ygqUK5PJUoEKR9UcDg0uCKx4Zofpm46WCHx8w8M0+Abss8a1wJAA5JqFDDli44zxLKjJ5T63wdw4PhFyDDQQS3gdE3VG5GlDiifrEABjyuX1p90leAcvENPNJq71jOqqgFCni02YQJAQ8q09SA54lNA0qOwyJhOEFtsCxGAB9/i70a18uqh7f4IxUOIyADFVeQDF6zOcqK90EYg96Ltsuf/on1hnCgAnQJBANGvRflfL1Xvelv2jb446Gnq83IwQ6WJvO8z7/awfMmDsC88MI2bE0xcWJ2QPZZEVJkgCmwOXc26G+z0eei/z/U=";
				
		// 接收表单提交参数(To receive the parameter)
		request.setCharacterEncoding("UTF-8");			
		String interface_version = (String) request.getParameter("interface_version");
		String service_type = (String) request.getParameter("service_type");
		String input_charset = (String) request.getParameter("input_charset");
		String sign_type = request.getParameter("sign_type");
		String merchant_code = (String) request.getParameter("merchant_code");
		String mobile = (String) request.getParameter("mobile");				
		String bank_code = (String) request.getParameter("bank_code");
		String card_type = (String) request.getParameter("card_type");
		String card_no = (String) request.getParameter("card_no");
		
		/** 数据签名
		签名规则定义如下：
		（1）参数列表中，除去sign_type、sign两个参数外，其它所有非空的参数都要参与签名，值为空的参数不用参与签名；
		（2）签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，组成规则如下：
		参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n
		*/

		/** Data signature
		The definition of signature rule is as follows : 
		（1） In the list of parameters, except the two parameters of sign_type and sign, all the other parameters that are not in blank shall be signed, the parameter with value as blank doesn’t need to be signed; 
		（2） The sequence of signature shall be in the sequence of parameter name from a to z, in case of same first letter, then in accordance with the second letter, so on so forth, the composition rule is as follows : 
		Parameter name 1 = parameter value 1& parameter name 2 = parameter value 2& ......& parameter name N = parameter value N 
		*/
		
		StringBuffer signSrc= new StringBuffer();	
		signSrc.append("bank_code=").append(bank_code).append("&");
		signSrc.append("card_no=").append(card_no).append("&");
		signSrc.append("card_type=").append(card_type).append("&");	
		signSrc.append("input_charset=").append(input_charset).append("&");
		signSrc.append("interface_version=").append(interface_version).append("&");
		signSrc.append("merchant_code=").append(merchant_code).append("&");
		signSrc.append("mobile=").append(mobile).append("&");	
		signSrc.append("service_type=").append(service_type);
		
				
		String signInfo =signSrc.toString();
		String sign = "" ;
		if("RSA-S".equals(sign_type)) {
				
			sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key) ;  // 商家签名（签名后报文发往dinpay）
			System.out.println("RSA-S商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
			System.out.println("RSA-S商家发送的签名：" + sign.length() + " -->" + sign + "\n");
		}else{
		
			String merPfxPath = "D:/108008008666.pfx";  // 商家的pfx证书文件位置(公私钥合一)
			String pfxPass = "87654321";			  // 商家的pfx证书密码
			RSAWithHardware mh = new RSAWithHardware();			
			mh.initSigner(merPfxPath, pfxPass);		  // 设置密钥			
			sign = mh.signByPriKey(signInfo);		  // 商家签名（签名后报文发往dinpay）
			System.out.println("RSA商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
			System.out.println("RSA商家发送的签名：" + sign.length() + " -->" + sign + "\n");
		}
				
%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
</head>
<body onLoad="document.dinpayForm.submit();">
  <form name="dinpayForm" method="post" action="https://api.tearfxwn.com/gateway/api/express" >
	<input type="hidden" name="sign" value="<%=sign%>" />
	<input type="hidden" name="interface_version" value="<%=interface_version%>"/>
	<input type="hidden" name="service_type" value="<%=service_type%>"/>
	<input type="hidden" name="input_charset" value="<%=input_charset%>">	
	<input type="hidden" name="sign_type" value="<%=sign_type%>"/>	
	<input type="hidden" name="merchant_code" value="<%=merchant_code%>"/>
	<input type="hidden" name="mobile" value="<%=mobile%>"/>
	<input type="hidden" name="bank_code" value="<%=bank_code%>"/>	
	<input type="hidden" name="card_type" value="<%=card_type%>"/>	
	<input type="hidden" name="card_no" value="<%=card_no%>">
  </form>
</body>
</html>