<%
/* *

 **********************************************
 */
%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="com.pay.config.*"%>
<%@ page import="com.pay.util.*"%>
<%@ page import="java.util.*"%>
<%@ page import="com.alibaba.fastjson.*"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>网关代付交易接口</title>
	</head>
	<%		
			request.setCharacterEncoding("UTF-8"); 

			String inputCharset= request.getParameter("inputCharset");
			//String partnerId=request.getParameter("partnerId");
			String partnerId=PayConfig.partner;
			
			String signType=request.getParameter("signType");
			String orderNo=request.getParameter("orderNo");
			
			String sign="";
			//////////////////////////////////////////////////////////////////////////////////
			
			//把请求参数打包成数组
			TreeMap<String,String> params = new TreeMap<String,String>();
			params.put("inputCharset", inputCharset);
			params.put("partnerId", partnerId);
			
			
			params.put("orderNo", orderNo);
			
			//加密私钥
			String cus_private_key=PayConfig.cusPrivateKey;
			//String cus_private_key="MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAMv/IjDfpTHrYPpyTe0WyJYW3+m77yWp2Hxsigf6H7MuGYyw0MumiqCj/bI5QvKZhxnEcD0TVOA+C7atvG/6/mBCgDjFYiIkcQ/0NWAVyyT/6C/30jINqJ7HI4fFzf+g+R3rhWcJ8yOM3E/1lEQE67NERSjDY8tOhqlmtuDBaCsdAgMBAAECgYEAv4PEPqwIM+huAFJFlHtaT4YkKxRz/SEKjC1+HOUW06pI9EufikHNTekHqUWW85ltO6SvVreKbIfziUpsaZjzLA8c5JmcRY4g9w3aRcVbV8HcP0sE4/Rib/JGhCopgwcGnG2iwjyw258Y6TUZlhVecMMy5osxxk3hU+Q81H0XfSUCQQD5r8iLAJ0RY6uYQe9ftL2410YtD9nMHCiDeZ2B2XZwdaBUUDsam5+BH2EH0qWpbQvSUpJkXtg535qZCsOg1vLvAkEA0SeZeMKCzh1LMyHjea5QFrjVSnCqKOYL1kiJ5RlmSnUjYC5AtkOSaLZlPYX9YJCGKRCRHI7V5f/UM2EkVHCSswJBAOZyZi5c75qoGizZ1hvIDj72eW+HrKXk60OFUGkTE2xyM/r9Xb+OGKYtFvoIYhvAaGPDEBgRLZIknWRY+fuNyAMCQHMi1nRIt1MZgyURubRpRcNMWnXREYrUIJ4EboyEb+/7Dc9LhuoOxpEIHzFAClxXEtOWQBu1cYBcVYc3KZWmJssCQQCzUvY8vSwnwKb07CaDixScPbjRWyFMX3eJLO7I3rpQdqO2+R8ujREvy8GAvYlSYkgyD1/lUbXT7p1EWbdVmgxL";
			params.put("signMsg", RSASignature.sign(params, cus_private_key));//使用私钥签名
			params.put("signType", signType);//signType等签名完了再放进去
			
		
			
			//建立请求
			//统一支付入口
			String gateWay = PayConfig.WITHDRAW_QUERY_URL;
		
			HttpConnectionUtil http = new HttpConnectionUtil(gateWay);
			http.init();
			//String sHtmlText = YlPaySubmit.buildRequest(params,"POST",payType);
			byte[] bys = http.postParams(params, true);
			String result = new String(bys,"UTF-8");
			
			out.println(result);
			
			JSONObject jsonobj = JSON.parseObject(result);
			out.println("<br>errCode [0000-提交成功]："+jsonobj.getString("errCode"));
			out.println("<br>errMsg："+jsonobj.getString("errMsg"));
			out.println("<br>payResult【1-未处理，2-处理中，3-成功，4-失败】 ："+jsonobj.getString("payResult"));
			out.println("<br>orderAmount 金额（分）："+jsonobj.getString("orderAmount"));
			
	%>
	<body>
	</body>
</html>
