<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.Date" %>
<%@ page import="java.text.SimpleDateFormat" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%
	SimpleDateFormat df = new SimpleDateFormat("yyyyMMddHHmmss");//set date format
	String dateTmp = df.format(new Date());// get current system time
%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Order Info</title>
</head>
<body>
<div align="center">
<h2>Internal demonstration of scan code payment system</h2>
<h1>Submit order info</h1>
	<form action="pay/reqpay.jsp">
		<table>
			<tr>
				<td>Charset:</td>
				<td><input name="charset" id="charset" type="text" value="UTF-8"/></td>
			</tr>
			<tr>
				<td>Merchant Code:</td>
				<td><input name="merchantCode" id="merchantCode" type="text" value="M000TEST"/></td>
			</tr>
			<tr>
				<td>Order No:</td>
				<td><input name="orderNo" id="orderNo" type="text" value="<%=dateTmp%>"/></td>
			</tr>
			<tr>
				<td>Order Amount:</td>
				<td><input name="amount" id="amount" type="text" value="1"/>   cents</td> 
			</tr>
			<tr>
				<td>Payment channel</td>
				<td>
					<select name="channel" id="channel">
						<option value="WEIXIN">WEIXIN</option>
						<option value="ALIPAY">Alipay</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Order Description:</td>
				<td><input name="remark" id="remark" type="text" value="Online Trading"/> </td> 
			</tr>
			<tr>
				<td>Asynchronous notification:</td>
				<td><input name="notifyUrl" id="notifyUrl" type="text" value="http://localhost:8080/pm/a.do"/></td> 
			</tr>
			<tr>
				<td>Synchronous notification:</td>
				<td><input name="returnUrl" id="returnUrl" type="text" value="http://localhost:8080/pm/b.do"/></td> 
			</tr>
			<tr>
				<td>Sign type:</td>
				<td><input name="signType" id="signType" type="text" value="RSA"/></td> 
			</tr>
			<tr>
				<td><input type="submit" value="Submit"/> </td> 
			</tr>
		</table>
	</form>
	
	<h1>Order Inquiry</h1>
	<form action="pay/reqqrypayorder.jsp">
		<table>
			<tr>
				<td>Merchant Code:</td>
				<td><input name="merchantCode" id="merchantCode" type="text" value="M000TEST"/></td>
			</tr>
			<tr>
				<td>Order No:</td>
				<td><input name="orderNo" id="orderNo" type="text" value=""/></td>
			</tr>
			<tr>
				<td>Sign type:</td>
				<td><input name="signType" id="signType" type="text" value="RSA"/></td> 
			</tr>
			<tr>
				<td><input type="submit" value="Inquiry"/> </td> 
			</tr>
		</table>
	</form>
</div>
</body>
</html>