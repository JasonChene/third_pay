<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
//////////////////////////////////// 接收参数 request parameters //////////////////////////////////////

		// 接收智付返回的参数(To receive the parameter)
		request.setCharacterEncoding("UTF-8");
		String interface_version = (String) request.getParameter("interface_version");
		String merchant_code = (String) request.getParameter("merchant_code");
		String notify_type = (String) request.getParameter("notify_type");
		String notify_id = (String) request.getParameter("notify_id");
		String sign_type = (String) request.getParameter("sign_type");
		String dinpaySign= (String) request.getParameter("sign");
		String order_no = (String) request.getParameter("order_no");
		String order_time = (String) request.getParameter("order_time");
		String order_amount = (String) request.getParameter("order_amount");
		String extra_return_param = (String) request.getParameter("extra_return_param");
		String trade_no = (String) request.getParameter("trade_no");
		String trade_time= (String) request.getParameter("trade_time");
		String trade_status = (String) request.getParameter("trade_status");
		String bank_seq_no= (String) request.getParameter("bank_seq_no");
			
		if("SUCCESS".equals(trade_status)){
			
		System.out.println(	"交易成功！！！ " + "\n" +
							"interface_version = " + interface_version + "\n" + 
							"merchant_code = " + merchant_code + "\n" +
							"notify_type = " + notify_type + "\n" +
							"notify_id = " + notify_id + "\n" +
							"sign_type = " + sign_type + "\n" +
							"dinpaySign = " + dinpaySign + "\n" +
							"order_no = " + order_no + "\n" +
							"order_time = " + order_time + "\n" +
							"order_amount = " + order_amount + "\n" +
							"extra_return_param = " + extra_return_param + "\n" +
							"trade_no = " + trade_no + "\n" +
							"trade_time = " + trade_time + "\n" +
							"trade_status = " + trade_status + "\n" +
							"bank_seq_no = " + bank_seq_no + "\n" 	); 	
			
					
		
		}else{
			System.out.println(	"交易失败！！！ " + "\n" );
			
		}
							
%>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
</head>
<body>
</body>
</html>
