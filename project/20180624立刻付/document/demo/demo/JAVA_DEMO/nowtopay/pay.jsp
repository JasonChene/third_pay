<%@page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@page import="java.net.*" %>
<%@page import="com.nowtopay.*"%>
<%

    MD5  md5=new MD5();
	String  ordernumber=request.getParameter("ordernumber");
	String  banktype=request.getParameter("banktype");
	String  paymoney=request.getParameter("paymoney");
	String  attach=request.getParameter("attach");
	
	String partner=pay_config.partner;
	String key=pay_config.key;
	String apiurl=pay_config.apiurl;
	String notify_url=pay_config.notify_url;
	String return_url=pay_config.return_url;	
	String signSource = "partner="+partner+"&banktype="+banktype+"&paymoney="+paymoney+"&ordernumber="+ordernumber+"&callbackurl="+notify_url;  
	String sn=signSource+key;
	String sign=md5.toMD5(sn);
	response.sendRedirect(apiurl+"?"+signSource+"&hrefbackurl="+return_url+"&attach="+attach+"&sign="+sign);

%>
