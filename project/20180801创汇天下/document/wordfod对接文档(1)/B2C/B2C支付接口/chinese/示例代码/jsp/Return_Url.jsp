<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.io.*" %>
<%@ page import="com.itrus.util.sign.*" %>

<%
////////////////////////////////////  同步通知  ////////////////////////////////////

		// 接收返回的参数
		request.setCharacterEncoding("UTF-8");
		String interface_version = (String) request.getParameter("interface_version");
		String merchant_code = (String) request.getParameter("merchant_code");
		String notify_type = (String) request.getParameter("notify_type");
		String notify_id = (String) request.getParameter("notify_id");
		String sign_type = (String) request.getParameter("sign_type");
		String dinpaySign = (String) request.getParameter("sign");
		String order_no = (String) request.getParameter("order_no");
		String order_time = (String) request.getParameter("order_time");
		String order_amount = (String) request.getParameter("order_amount");
		String extra_return_param = (String) request.getParameter("extra_return_param");
		String trade_no = (String) request.getParameter("trade_no");
		String trade_time = (String) request.getParameter("trade_time");
		String trade_status = (String) request.getParameter("trade_status");
		String bank_seq_no = (String) request.getParameter("bank_seq_no");
	    String orginal_money= request.getParameter("orginal_money");	
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
							"bank_seq_no = " + bank_seq_no + "\n" 	+
							"orginal_money = " + orginal_money + "\n" ); 			
						
			out.println(	"交易成功！！！ " + "<br>" +
							"interface_version = " + interface_version + "<br>" + 
							"merchant_code = " + merchant_code + "<br>" +
							"notify_type = " + notify_type + "<br>" +
							"notify_id = " + notify_id + "<br>" +
							"sign_type = " + sign_type + "<br>" +
							"dinpaySign = " + dinpaySign + "<br>" +
							"order_no = " + order_no + "<br>" +
							"order_time = " + order_time + "<br>" +
							"order_amount = " + order_amount + "<br>" +
							"extra_return_param = " + extra_return_param + "<br>" +
							"trade_no = " + trade_no + "<br>" +
							"trade_time = " + trade_time + "<br>" +
							"trade_status = " + trade_status + "<br>" +
							"bank_seq_no = " + bank_seq_no + "<br>"   +
                            "orginal_money = " + orginal_money + "<br>" ); 
		
		}else{
			System.out.println(	"交易失败！！！ " + "\n" );
			out.println( "交易失败！！！ " + "<br>" );
		}
		
		System.out.println("---------------------------------------------------------------------------------------------------------------------------------------------");
							
%>
