<%@page import="com.zspay.SDK.util.MD5Encrypt"%>
<%@page import="java.security.NoSuchAlgorithmException"%>
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="java.lang.*"%>
	<%
	String md5key = "123456ADSEF";
	String merchantCode = request.getParameter("merchantCode");
	String instructCode = request.getParameter("instructCode");
	String transType = request.getParameter("transType");
	String outOrderId = request.getParameter("outOrderId");
	String transTime = request.getParameter("transTime");
	String totalAmount = request.getParameter("totalAmount");
	String backSign = request.getParameter("sign");
	String signsrc = String.format("instructCode=%s&merchantCode=%s&outOrderId=%s&totalAmount=%s&transTime=%s&transType=%s&KEY=%s", instructCode,
			merchantCode, outOrderId, totalAmount, transTime, transType, md5key);
	String sign = "";
	try {
		sign = MD5Encrypt.getMessageDigest(signsrc);
	} catch (NoSuchAlgorithmException e) {
		e.printStackTrace();
	}
	if(sign.equals(backSign)) {
		response.getWriter().println("{'code':'00'}");
		// TODO XXX
	} else {
		response.getWriter().println("{'code':'01','msg':'验签失败'}");
	}
	%>
