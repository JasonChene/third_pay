<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 请求参数 request parameters //////////////////////////////////////
		
		// encryption_key,加密密钥 
        String encryption_key ="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCbT0QjejG8pj8y/HfmzL7j9gHFKTwSg68ZiOad4ErxkSi/N77HnG+scCXVnkvvBwPTFI35AStGQnv3E/7XeCO8swvVR8J4w/k5JfQwcHnSk64DD0L+0KH6cRN27WZYdL9n4r+jW6OXILnbw0t1YM0/y6cOCc4R8yyd1hTCdgP3LwIDAQAB";
	
		// merchant_private_key,商家私钥  
		String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOD/K22eGec3qNQmk9LwsjpbJDJE9JYfsTJJQGJhfWsKbcZ9UISKXZxuhSCVaf2z9/pEln5RoE7GNwOrYv8R00P8nRJONHNPaLcf0Y8+c6DBWGVewZKojUzn18uAEGGW5XMjLs5/OU//opRB4ieeSmBJ4jp954XfR4Z57bjOpe/3AgMBAAECgYEArCr2K2JQxfp0aSq/8SkX6Mm3T/QuCPZlXGprJx0coJ0RVVKtG07ZxQtZOY671VQyjEKRukVx2vWYQWmTTkVwl+U71fh1mmiu00Y3odNoERc02ZN0zJmrSuhbcuEv6F8kBATunB55wOZ3jlbkXD9h+KUyePBOkrPb+81LhJ6kZXkCQQD18nQ1U2m9laS8ROJmZ1LuecQ4maaHW3xFxHoM9sS1YcpB3peQuXBrKa483zYADIJV2NYstc0QXMMZIXleKFFzAkEA6jF+xx4q+p/lhH8M3rHucHmkgFce90Jh1eHTdx5czizl3LiOYZ5D7cNL8x7piJDMmzkVz8+OidXm0wf5aT82bQJAP9TSJjjk26hn3dj+7Vbppi0CKTJvjvfGdBD/IDg3a1/a72eG7K/EJnvl1bSUvkSA2yjwxR/V/eYlWHNgnXhXUwJBANA6h+3FfhNvXmSrjqbncAljrwdJ70eMJ29DpoFQZtYPB6Z0FmzniqB6OCqIPr7leHc/j4xBkQwvO1hBy9pvkRUCQEVOGouGVeiXL/MuupUdbdBSV4nkYb9hrqE11gzbLu4A+OCpV8Xwdqu5SqX9Js1mQ6vQwTHu63vyfpxxl7oN9Jw=";
				
		// 接收表单提交参数(To receive the parameter)
		request.setCharacterEncoding("UTF-8");	
		String interface_version = (String) request.getParameter("interface_version");
		String service_type = (String) request.getParameter("service_type");
		String input_charset = (String) request.getParameter("input_charset");
		String sign_type = request.getParameter("sign_type");
		String merchant_code = (String) request.getParameter("merchant_code");
		String notify_url = (String) request.getParameter("notify_url");
		String order_no = (String) request.getParameter("order_no");
		String order_amount = (String) request.getParameter("order_amount");
		String order_time = (String) request.getParameter("order_time");
		String mobile = (String) request.getParameter("mobile");
		String bank_code = (String) request.getParameter("bank_code");
		String card_type = (String) request.getParameter("card_type");
		String product_name = (String) request.getParameter("product_name");
															
		String card_no = (String) request.getParameter("card_no");
		String card_name = (String) request.getParameter("card_name");
		String id_no = (String) request.getParameter("id_no");		
		// 敏感数据加密域
		String encrypt_info = card_no+"|"+card_name+"|"+id_no ;
		System.out.println("encrypt_info：" + encrypt_info.length() + " -->" + encrypt_info);
		// 加密
		String encrypt_info_result = RSAWithSoftware.encryptByPublicKey(encrypt_info,encryption_key) ;
		
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
		signSrc.append("card_type=").append(card_type).append("&");
		signSrc.append("encrypt_info=").append(encrypt_info_result).append("&");		
		signSrc.append("input_charset=").append(input_charset).append("&");
		signSrc.append("interface_version=").append(interface_version).append("&");
		signSrc.append("merchant_code=").append(merchant_code).append("&");				
		signSrc.append("mobile=").append(mobile).append("&");	
		signSrc.append("notify_url=").append(notify_url).append("&");	
		signSrc.append("order_amount=").append(order_amount).append("&");
		signSrc.append("order_no=").append(order_no).append("&");
		signSrc.append("order_time=").append(order_time).append("&");
		signSrc.append("product_name=").append(product_name).append("&");
		signSrc.append("service_type=").append(service_type);
	
		
		String signInfo =signSrc.toString();
		String sign = "" ;
// 		if("RSA-S".equals(sign_type)) {
					
			sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key) ;  // 商家签名（签名后报文发往dinpay） 
			System.out.println("RSA-S商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo); 
			System.out.println("RSA-S商家发送的签名：" + sign.length() + " -->" + sign + "\n");
// 		}else{
		
// 			String merPfxPath = "D:/108008008666.pfx";  // 商家的pfx证书文件位置(公私钥合一)
// 			String pfxPass = "87654321";              // 商家的pfx证书密码
// 			RSAWithHardware mh = new RSAWithHardware();			
// 			mh.initSigner(merPfxPath, pfxPass);       // 设置密钥
// 			sign = mh.signByPriKey(signInfo);         // 商家签名（签名后报文发往dinpay）
// 			System.out.println("RSA商家发送的签名字符串：" + signInfo.length() + " -->" + signInfo);
// 			System.out.println("RSA商家发送的签名：" + sign.length() + " -->" + sign + "\n");
// 		}
				
%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
</head>
<body onLoad="document.dinpayForm.submit();">
  <form name="dinpayForm" method="post" action="https://api.zdfmf.com/gateway/api/express" >
	<input type="hidden" name="sign" value="<%=sign%>" />
	<input type="hidden" name="encrypt_info" value="<%=encrypt_info_result%>" />
	<input type="hidden" name="interface_version" value="<%=interface_version%>"/>
	<input type="hidden" name="service_type" value="<%=service_type%>"/>
	<input type="hidden" name="input_charset" value="<%=input_charset%>">	
	<input type="hidden" name="sign_type" value="<%=sign_type%>"/>	
	<input type="hidden" name="merchant_code" value="<%=merchant_code%>"/>		
	<input type="hidden" name="notify_url" value="<%=notify_url%>"/>
	<input type="hidden" name="order_no" value="<%=order_no%>"/>	
	<input type="hidden" name="order_amount" value="<%=order_amount%>"/>
	<input type="hidden" name="order_time" value="<%=order_time%>"/>
	<input type="hidden" name="mobile" value="<%=mobile%>"/>	
	<input type="hidden" name="bank_code" value="<%=bank_code%>"/>	
	<input type="hidden" name="card_type" value="<%=card_type%>"/>		
	<input type="hidden" name="product_name" value="<%=product_name%>"/>	
  </form>
</body>
</html>