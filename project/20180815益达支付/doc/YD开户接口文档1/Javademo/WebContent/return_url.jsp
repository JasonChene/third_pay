<%

%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.*"%>
<%@ page import="java.util.Map"%>
<%@ page import="com.pay.util.*"%>
<%@ page import="com.pay.config.*"%>
<html>
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>页面跳转同步通知页面</title>
  </head>
  <body>
<%
	//获取GET过来反馈信息
	Map<String,String> params = new HashMap<String,String>();
	Map requestParams = request.getParameterMap();
	for (Iterator iter = requestParams.keySet().iterator(); iter.hasNext();) {
		String name = (String) iter.next();
		String[] values = (String[]) requestParams.get(name);
		String valueStr = "";
		for (int i = 0; i < values.length; i++) {
	valueStr = (i == values.length - 1) ? valueStr + values[i]
	: valueStr + values[i] + ",";
		}
		//乱码解决，这段代码在出现乱码时使用。如果mysign和sign不相等也可以使用这段代码转化
		valueStr = new String(valueStr.getBytes("ISO-8859-1"), "utf-8");
		params.put(name, valueStr);
	}
	
	//获取的通知返回参数，可参考技术文档中页面跳转同步通知参数列表(以下仅供参考)//
	//商户订单号
	String orderNo = params.get("orderNo");

	//交易号
	String trade_no = params.get("paymentOrderId");

	//交易状态
	String trade_status = params.get("payResult");
	
	//交易状态
	String extraCommonParam = params.get("extraCommonParam");

	//获取的通知返回参数，可参考技术文档中页面跳转同步通知参数列表(以上仅供参考)//
	
	//计算得出通知验证结果
	boolean verify_result = PayNotify.verify(params);
	payCore.logResult("平台同步通知 订单号 ：" + orderNo);
	payCore.logResult("平台同步通知 交易号 ：" + trade_no);
	payCore.logResult("平台同步通知 交易状态 ：" + trade_status);
	if(verify_result){//验证成功
		//////////////////////////////////////////////////////////////////////////////////////////
		//请在这里加上商户的业务逻辑程序代码
		payCore.logResult("平台同步通知 验证成功. ");
		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		if(trade_status.equals("1") ){
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
		}
		
		//该页面可做页面美工编辑
		out.println("验证成功<br />");
		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

		//////////////////////////////////////////////////////////////////////////////////////////
	}else{
		payCore.logResult("平台同步通知 验证失败. ");
		//该页面可做页面美工编辑
		out.println("验证失败");
	}
%>
  </body>
</html>