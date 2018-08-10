<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
	<form method="post" action="bankPay.jsp" target="_blank">
		<!--the parameters with "*" means it was required  -->
		<br>merchant_code：<input Type="text" Name="merchant_code" value="1111110166"> *		
		<br>service_type：<input Type="text" Name="service_type" value="direct_pay"> *
		<br>interface_version：<input Type="text" Name="interface_version" value="V3.0"/> *
		<br>input_charset：
			<select name="input_charset">
				<option value="UTF-8">UTF-8</option>
				<option value="GBK">GBK</option>
			</select> *		
		<br>notify_url：<input Type="text" Name="notify_url" value="http://15l0549c66.iask.in:45191/bankPay/offline_notify.jsp"> *																							
		<br>sign_type：
			<select name="sign_type">
				<option value="RSA-S">RSA-S</option>
				<option value="RSA">RSA</option>
			</select> *	
		<br>order_no：<input Type="text" Name="order_no" value="<%=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date())%>"> *																															
		<br>order_time：<input Type="text" Name="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>*		
		<br>order_amount：<input Type="text" Name="order_amount" value="0.1"> *				
		<br>product_name：<input Type="text" Name="product_name" value="test"> *
		
		<br>return_url：<input Type="text" Name="return_url" value="http://15l0549c66.iask.in:45191/bankPay/page_notify.jsp"> 
		<br>bank_code：<input Type="text" Name="bank_code" value=""> 
		<br>redo_flag：
			<select name="redo_flag">
				<option value="1">yes</option>
				<option value="0">no</option>
				<option value="">empty</option>
			</select> 
		<br>product_code：<input Type="text" Name="product_code" value="">
		<br>product_num：<input Type="text" Name="product_num" value="">
		<br>product_desc：<input Type="text" Name="product_desc" value="">
		<br>pay_type：<input Type="text" Name="pay_type" value=""> 
		<br>client_ip：<input Type="text" Name="client_ip" value=""> 
		<br>extend_param：<input Type="text" Name="extend_param" value=""> 
		<br>extra_return_param：<input Type="text" Name="extra_return_param" value=""> 
		<br>show_url：<input Type="text" Name="show_url" value=""> 
		<br><input Type="submit" Name="submit" value="QuickPayPay">
	</form>
  </body>
</html>
