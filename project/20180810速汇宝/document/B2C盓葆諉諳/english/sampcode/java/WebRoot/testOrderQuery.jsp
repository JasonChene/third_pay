<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
	<form method="post" action="orderQuery.jsp" target="_blank">
		<br>merchant_code：<input Type="text" Name="merchant_code" value="1111110166"> *				
		<br>service_type：<input Type="text" Name="service_type" value="single_trade_query"> *
		<br>interface_version：<input Type="text" Name="interface_version" value="V3.0"/> *		
		<br>sign_type：
			<select name="sign_type">
				<option value="RSA-S">RSA-S</option>
				<option value="RSA">RSA</option>
			</select> *	
		<br>order_no：<input Type="text" Name="order_no" value=""> *																																	
		<br>trade_no：<input Type="text" Name="trade_no" value=""> 				
		<br><input Type="submit" Name="submit" value="Orderquery">
	</form>
  </body>
</html>
