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
		<title>网关支付交易接口</title>
	</head>
	<%		
			request.setCharacterEncoding("UTF-8"); 

			String inputCharset= request.getParameter("inputCharset");
			//String partnerId=request.getParameter("partnerId");
			String partnerId=PayConfig.partner;
			String returnUrl=request.getParameter("returnUrl");
			String notifyUrl=request.getParameter("notifyUrl");
			String signType=request.getParameter("signType");
			String orderNo=request.getParameter("orderNo");
			String orderAmount=request.getParameter("orderAmount");
			String orderDatetime=request.getParameter("orderDatetime");
			String orderCurrency=request.getParameter("orderCurrency");

			String subject=request.getParameter("subject");
			String body=request.getParameter("body");
			String extraCommonParam=request.getParameter("extraCommonParam");
			String payMode=request.getParameter("payMode");
			
			String bnkCd=request.getParameter("bnkCd");
			String accTyp=request.getParameter("accTyp");

			

			//加密私钥
			//String cus_private_key=request.getParameter("cus_private_key");

			String cus_private_key=PayConfig.cusPrivateKey;
			
			
			String sign="";
			//////////////////////////////////////////////////////////////////////////////////
			
			//把请求参数打包成数组
			TreeMap<String,String> params = new TreeMap<String,String>();
			params.put("inputCharset", inputCharset);
			params.put("partnerId", partnerId);
			
			params.put("notifyUrl", notifyUrl);
			params.put("returnUrl", returnUrl);
			params.put("orderNo", orderNo);
			params.put("orderAmount", orderAmount);
			params.put("orderCurrency", orderCurrency);
			params.put("orderDatetime", orderDatetime);
			params.put("payMode", payMode);
			//params.put("isPhone", isPhone);
			params.put("subject", subject);
			params.put("body", body);
			
			params.put("bnkCd", bnkCd);
			params.put("accTyp", accTyp);
			
			System.out.println(cus_private_key);
			//String pubkey="MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC6tBzCx/yHSNk2GpNM38BxCwSbc8iNHgkKL+46EpRvUQxw5sSNOdkAEmk071ySbUrg8mOOT7QY7DKJ27cyRi/tjfBdwXE7NzWvSsxZ+Eouv118vuTwc5FiNGDxQ/56LHx4J99WAAtQZL1VdMVHAtHyqJ9Y/PN+LEP3TD2GF0LF1QIDAQAB";
    		//cus_private_key="MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALq0HMLH/IdI2TYak0zfwHELBJtzyI0eCQov7joSlG9RDHDmxI052QASaTTvXJJtSuDyY45PtBjsMonbtzJGL+2N8F3BcTs3Na9KzFn4Si6/XXy+5PBzkWI0YPFD/nosfHgn31YAC1BkvVV0xUcC0fKon1j8834sQ/dMPYYXQsXVAgMBAAECgYBwejWhes29yrthBt2awGm1MIgUBsrZiTC0/G8ueScHcPJnyf67fhAnJPBQvastY75qilxDzeyX3yQLPVy+Yt5X3XFnJF3NvOp0xn209Pe0K4fx73tTOFHL42Cfvs9jAWw7N9y6OGsdin6R2vLS/bSbSBGbbWT9jgByQs7QxS3pwQJBANtw5VdsMkR8Qf+DSc0yN0aFAJoWYCWAdqg2A4/QljC3j6KrARFsvQ8JCZsYUtRWtbY+g1Bx6VxM0n9A+/Tte60CQQDZzvkVXy/2yDMMh8rqSd913L3rvWO8iIj1Km2wI45qcUYfTYCCiTe0tOdM0EDA+ES8c6rY5IiQb3wHJARHdLfJAkEAk5XZWTO2lyLvDFcTUsN8M5yOLBPydCZzJ2y0dov2ByvdmazjGgIFIVCVuk7gnlj2+2wNyxPhvCcax2VAT5lNPQJAdeHtMcH45ano5yk/i5o71UCJkeNI7as/5OD3yNMVq0pvV1XM7dlySt12Kj60LTyxhwVbPFREDVgpWnwFzN7A8QJAWJeki0r8Hr2CYHrqiLF/LJZ7JS3/FxeJGK7ASL8Qt1WdViX4ZJN90/sMFHIMsNcJEM6VO4oCO3kTEW7X7Y7TRQ==";
			params.put("signMsg", RSASignature.sign(params, cus_private_key));//使用私钥签名
			params.put("signType", signType);//signType等签名完了再放进去
			

			//sParaTemp.put("extraCommonParam", extraCommonParam); 
			
			//建立请求
			//统一支付入口
			String gateWay = PayConfig.UNIFY_PAY_URL;
		
			HttpConnectionUtil http = new HttpConnectionUtil(gateWay);
			http.init();
			//String sHtmlText = YlPaySubmit.buildRequest(params,"POST",payType);
			byte[] bys = http.postParams(params, true);
			String result = new String(bys,"UTF-8");
			
			
			out.println(result);
			
			JSONObject jsonobj = JSON.parseObject(result);
			if("0000".equals(jsonobj.getString("errCode"))){
				if("5".equals(payMode)){
					out.println("请使用QQ扫码支付，以下是支付二维码地址，请自行解析<br>");
					out.println(jsonobj.getString("qrCode"));
				}
				if("3".equals(payMode)){
					out.println("网银支付，将会自动根据返回html里定义的地址跳转到快捷收银台<br>");
					String html  = jsonobj.getString("retHtml");
					html = html.replace("\n", "<br>");
					out.println(html);
				}
				if("4".equals(payMode)){
					out.println("快捷支付，将会自动根据返回html里定义的地址跳转到快捷收银台<br>");
					String html  = jsonobj.getString("retHtml");
					html = html.replace("\n", "<br>");
					out.println(html);
				}
				if("9".equals(payMode)){
					//qqwap，手机端打开，会自动跳转，如果不会跳转则自行解析json
				}
			}else{
				out.println(jsonobj.getString("errCode"));
			}
	%>
	<body>
	</body>
</html>
