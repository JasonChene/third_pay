
<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head> 
  <body>
	<form method="post" action="QuerySignType.jsp" target="_blank">
		<br>接口版本(interface_version)：<input Type="text" Name="interface_version" value="V3.0"/> *
		<br>服务类型(service_type)：<input Type="text" Name="service_type" value="sign_query"> *
		<br>参数编码字符集(input_charset)
			<select name="input_charset">
				<option value="UTF-8">UTF-8</option>
				<option value="GBK">GBK</option>
			</select> *						
		<br>签名方式(sign_type)：
			<select name="sign_type">
				<option value="RSA-S">RSA-S</option>
				<option value="RSA">RSA</option>
			</select> *						
		<br>商 家 号(merchant_code)：<input Type="text" Name="merchant_code" value="200001001888"> *		
		<br>手机号(mobile)：<input Type="text" Name="mobile" value="13265627543"> *
		<br>银行代码(bank_code)：<input Type="text" Name="bank_code" value="ABC"> *		
		<br>银行卡类型(card_type)：
			<select name="card_type">
				<option value="0">借记卡</option>
				<option value="1">信用卡</option>
			</select> *				
		<br>银行卡号(card_no)：<input Type="text" Name="card_no" value="6228480128396157173">
		<br>-------------------------------------------------------------		
		<br><input Type="submit" Name="submit" value="提交查询签约参数">
	</form>
  </body>
</html>
