<%
/* *
 功能：科诺支付页面跳转同步通知页面
 说明：
 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 该代码仅供学习和研究MustPay接口使用，只是提供一个参考。

 * */
%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.*"%>
<%@ page import="java.util.Map"%>
<%@ page import="com.mustpay.util.*"%>
<%@ page import="com.mustpay.config.*"%>
<html>
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>科诺支付页面跳转同步通知页面</title>
  </head>
  <body>
<%
	//获取科诺支付的通知返回参数，可参考接入指南中同步通知API接口列表(以下仅供参考)//
	//商户订单号	String out_trade_no = new String(request.getParameter("out_trade_no").getBytes("ISO-8859-1"),"UTF-8");

	//交易金额	String total_fee = new String(request.getParameter("total_fee").getBytes("ISO-8859-1"),"UTF-8");

	//商品名称
	String subject = new String(request.getParameter("subject").getBytes("ISO-8859-1"),"UTF-8");

	//商品详情
	String body = new String(request.getParameter("body").getBytes("ISO-8859-1"),"UTF-8");
	
	//商户下单时传递的扩展字段，原样返回
	String extra = new String(request.getParameter("extra").getBytes("ISO-8859-1"),"UTF-8");
	
	//获取科诺支付的通知返回参数，可参考接入指南中同步通知API接口列表(以上仅供参考)//
	
	//注：同步返回接口未做私玥加密 请不要做逻辑处理，只进行查询跳转
%>
  </body>
</html>