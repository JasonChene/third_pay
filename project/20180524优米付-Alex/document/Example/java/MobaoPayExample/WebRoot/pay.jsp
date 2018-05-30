<%@page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@page import="com.mobo360.merchant.api.*"%>
<%@page import="java.io.PrintWriter"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>跳转中...</title>
	</head>
	<%
		try {
			// 初始化签名
			Mobo360SignUtil.init();
			// 组织请求数据
			Map<String, String> paramsMap = new HashMap<String, String>();
			request.setCharacterEncoding("UTF-8");
			paramsMap.put("apiName", Mobo360Config.MOBAOPAY_APINAME_PAY);
			paramsMap.put("apiVersion", Mobo360Config.MOBAOPAY_API_VERSION);
			paramsMap.put("platformID", Mobo360Config.PLATFORM_ID);
			paramsMap.put("merchNo", Mobo360Config.MERCHANT_ACC);
			paramsMap.put("orderNo", request.getParameter("orderNo"));
			paramsMap.put("tradeDate", request.getParameter("tradeDate"));
			paramsMap.put("amt", request.getParameter("amt"));
			paramsMap.put("merchUrl", Mobo360Config.MERCHANT_NOTIFY_URL);
			paramsMap.put("merchParam", request.getParameter("merchParam"));
			paramsMap.put("tradeSummary", request
					.getParameter("tradeSummary"));
			/**
             * bankCode为空，提交表单后浏览器在新窗口显示支付系统收银台页面，在这里可以通过账户余额支付或者选择银行支付；
             * bankCode不为空，取值只能是接口文档中列举的银行代码，提交表单后浏览器将在新窗口直接打开选中银行的支付页面。
             * 无论选择上面两种方式中的哪一种，支付成功后收到的通知都是同一接口。
             **/
			paramsMap.put("bankCode", request.getParameter("bankCode"));
			paramsMap.put("choosePayType", request.getParameter("choosePayType"));

			String paramsStr = Mobo360Merchant.generatePayRequest(paramsMap);	// 签名源数据
			String signMsg = Mobo360SignUtil.signData(paramsStr);	// 签名数据
			String epayUrl = Mobo360Config.MOBAOPAY_GETWAY;	//支付网关地址
			paramsMap.put("signMsg", signMsg);
		    System.out.println("(网关支付  签名后数据)"+paramsMap);
			// 生成表单并自动提交到支付网关。
			StringBuffer sbHtml = new StringBuffer();
			sbHtml
					.append("<form id='mobaopaysubmit' name='mobaopaysubmit' action='"
							+ epayUrl + "' method='post'>");
			for (Map.Entry<String, String> entry : paramsMap.entrySet()) {
				sbHtml.append("<input type='hidden' name='"
						+ entry.getKey() + "' value='" + entry.getValue()
						+ "'/>");
			}
			sbHtml.append("</form>");
			sbHtml
					.append("<script>document.forms['mobaopaysubmit'].submit();</script>");
			response.setCharacterEncoding("utf-8");
			PrintWriter writer = response.getWriter();
			writer.print(sbHtml.toString());
			writer.flush();
			writer.close();
		} catch (Exception e) {
			out.println(e.getMessage());
		}
	%>
	<body>
	</body>
</html>


