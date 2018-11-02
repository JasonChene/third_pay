<%@ page language="java" import="java.util.*" contentType="text/html; charset=UTF-8"%>
<%@ include file ="header.jsp"%>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>接口测试Demo</title>
</head>
<body>

	<h3 align="center">订单查询</h3>

	<br />

	<form action="orderQuerySubmit.jsp" method="post">
		<table align="center">
			<tr>
				<td>商户编号</td>
				<td><input type="text" name="merId" value="<%=merId%>">merId</td>
			</tr>
			<tr>
				<td>接口版本</td>
				<td><input type="text" name="version" value="1.0.0">version</td>
			</tr>
			
			<tr>
				<td>商户订单号</td>
				<td><input type="text" name="orderNo" value="<%=System.currentTimeMillis()+"" %>">orderNo</td>
			</tr>
			
			<tr>
				<td>商户MD5签名Key</td>
				<td><textarea name="mercKey" ><%=mercKey%></textarea></td>
			</tr>
			<tr>
				<td>提交URL</td>
				<td><textarea name="reqUrl" rows="1" cols="60"><%=baseUrl+"queryOrder.do" %></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><b><button type="submit">提交</button></b></td>
			</tr>
		</table>
	</form>
</body>
</html>