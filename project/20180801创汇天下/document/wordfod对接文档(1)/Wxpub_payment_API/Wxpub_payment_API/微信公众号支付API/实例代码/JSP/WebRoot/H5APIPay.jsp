<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="cn.zf.http.*" %>
<%@ page import="java.io.*" %>
<%@ page import="java.util.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 请求参数  ////////////////////////////////////////////////
			
		// 支付请求地址
		String reqUrl = "https://api.wordfod.com/gateway/api/h5apipay";
		// 支付请求返回结果
		String result = null;
		
		// 接收表单提交参数
		request.setCharacterEncoding("UTF-8");					
		String merchant_code = (String) request.getParameter("merchant_code");
		String service_type = (String) request.getParameter("service_type");
		String notify_url = (String) request.getParameter("notify_url");		
		String interface_version = (String) request.getParameter("interface_version");
		String client_ip = (String) request.getParameter("client_ip");
		String sign_type = (String) request.getParameter("sign_type");		
		String order_no = (String) request.getParameter("order_no");
		String order_time = (String) request.getParameter("order_time");
		String order_amount = (String) request.getParameter("order_amount");
		String product_name = (String) request.getParameter("product_name");
		
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
			
			/*
			1)merchant_private_key，商户私钥，商户按照《密钥对获取工具说明》操作并获取商户私钥；获取商户私钥的同时，也要获取商户公钥（merchant_public_key）；调试运行
			代码之前首先先将商户公钥上传到商家后台"支付管理"->"公钥管理"（如何获取和上传请查看《密钥对获取工具说明》），不上传商户公钥会导致调试运行代码时报错。
			2)demo提供的merchant_private_key是测试商户号Z800001001001的商户私钥，请自行获取商户私钥并且替换	*/	
			
			String merchant_private_key = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKn93SEpidIRKTYRqvjb6qW6iS8eorY/5nPwBE2xk7tdulDsWCZzLtt9oSCDZj6QTFXjXQZKO03cyvVPS5gZ1MIeW0ARkfcwdrJ6EawonKS909lIflCfCKCC9oCqHIhFXFmK9AU6UjcTwE5nwwb3K689Ng+6SEVjHMendtV3OTjDAgMBAAECgYBxx+QtADqpiqcE88p2i+yBRVvxWBYc2qSL0Ylv3348mT3OUIOoKMyiSXKB6rGTCs6tZmOrhCAxu6l1jL/SbOfEd33TSUmMSTAyLhq3Uc1kRa9D8u7hJHqHRJeG5NNU/rJy5t9ncBI9ktEKpWKQpix1WfqSsfeO+TKUfMNWOlDmIQJBANEqg7UrJ68n2rFpN281HDsVR12IQnBKyFtDqBZ33bWXR+yAXRexwLUvPZYaBuBEp9KcIBee9g0J6IY8W84Z5GsCQQDQDdsHOhAd01KhANnGz7FkIbac9vEohbovzlMeOPV7wXbsZR+ZrqJXzhbuvU8sjCGDItf5KRCtT+rjIofGJNMJAkEAos1WinK2hqycma3tic9q08nyLCjcnY53eCGm+SX/GVJQlxIqY0DlX6EPbH+Bjpmhjloa2IfPt8JYi/L6+eZJVQJANCfVCXm/wopQQ3ZAIbu9H3noGm85Q0xKwWM6qO/kcjKsilRLWK5TmilazFx+tY8nc4VPmPF3ccr/+hKU8NIYaQJBAL+bKSa+9N3aR1OnCfBf7Tf5hvCVCR7gKoo5llOH3yo+pNLBDdI4TDDueSoK0UD8t1nodrgZMc/sbch+9zWswQA=";	
			sign = RSAWithSoftware.signByPrivateKey(signInfo,merchant_private_key);	// 签名   signInfo签名参数排序，  merchant_private_key商户私钥  				
			reqMap.put("sign", sign);				
			result= new HttpClientUtil().doPost(reqUrl, reqMap, "utf-8");		 	// 向发送POST请求							
		}
		
		if("RSA".equals(sign_type)){ // 数字证书加密方式 sign_type = "RSA"
			
			// 请在商家后台"支付管理"->"证书下载"处申请和下载pfx数字证书，一般要1~3个工作日才能获取到，Z800001001001.pfx是测试商户号Z800001001001的数字证书
			String webRootPath = request.getSession().getServletContext().getRealPath("/");
			String merPfxPath = webRootPath + "pfx/Z800001001001.pfx"; 				// 商家的pfx证书文件路径
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
