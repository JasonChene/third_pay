<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>支付</title>
</head>
<body>
	这商户的取货页面.
	商户号:<%=request.getParameter("merchantCode") %><br />
	交易类型:<%=request.getParameter("transType") %><br />
	支付系统订单号:<%=request.getParameter("instructCode") %><br />
	商户订单号:<%=request.getParameter("outOrderId") %><br />
	交易时间:<%=request.getParameter("transTime") %><br />
	交易金额(单位:分):<%=request.getParameter("totalAmount") %>分<br />
</body>
</html>