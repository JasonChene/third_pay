<%@page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@page import="java.net.*" %>
<%@page import="com.pay.*"%>
<%

    MD5  md5=new MD5();
	String  ordernumber=request.getParameter("ordernumber");
	String  paytype=request.getParameter("paytype");
	String  paymoney=request.getParameter("paymoney");
	String  attach=request.getParameter("attach");
	
	String appid=pay_config.appid;
	String key=pay_config.key;
	String apiurl=pay_config.apiurl;
	String notify_url=pay_config.notify_url;	
	String signSource = "appid="+appid+"&paytype="+paytype+"&paymoney="+paymoney+"&ordernumber="+ordernumber+"&callbackurl="+notify_url;  
	String sn=signSource+key;
	String sign=md5.toMD5(sn);
	response.sendRedirect(apiurl+"?"+signSource+"&attach="+attach+"&sign="+sign);

%>
