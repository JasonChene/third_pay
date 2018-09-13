<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page import="com.pay.sign.*"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%
	String publicKeyStr = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCAYZ5gqFKPOOmiJk+IVstJPfS5DRnGIByjMOL0Waod0v2LHZO7tRubdsWti6JxjNS5Syu0G82YDCyhmEVwy0AE6ufrV7f3IhAQ9AJPkZCA9pCEjDSHtVtt3823A+PFtyQ1Lku+eWqcou+7IwT3uW2a6ZAb9VCcJmVbYFk+xkThdQIDAQAB";
	String privateKeyStr = "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKCVIMoYvd5NyrywDkneBWIfSJh8dbZZmMKk935v1Ve0ggDZEJ52cjYZyZNrWiExDZOdSQfoRYwaGRQZscrBlpEmoUhiKbQc3zCKl2wXZjqBzFRcgQ3jMhd5auhJWgZjjt0YowSk4hECoJ16X9TCT/FcWDw4GqZpRm/kwPD5O8GHAgMBAAECgYABNn8j56HvfujsGexRtIKX5iKXPEFrWivkNHjGFLQo0G028mzEtaJRNEqoeWQZ4hP3LHXzwFZeI4hS9Yq5PFMVzG7pNpq0Q+4nTzgkqiTzr7OBfsbgI0c9HLOjBi8Y31vTBbrDFt4zGRZVJErVBMh96SKZ4znndjK1f/IT55/OqQJBAP9HxRHio8QmbYtY9bBIK4pk8c/F9yVOAM5+EGYpPNTjBd/JFYx1Pp7rpzjVxMM6OMVwGO0O+gg8BmLJa/QR8m0CQQChCQRXCyOrxQzCgDQOXLlLmnu+tGjke+9kuDvLVoL8d+0sjPgUI2arXv41JATRT0kSw2gqkkymQN8VlEzNdStDAkAUbyxJODkftGvEYcSY7c3+cAIjPZeA9vN9k/3AD8D80Ydg2HnPGnt+wSJLnGD5t6lftI9qOm6tRhDEy5bGnMEpAkBSPISW3v1sbsjBWy86VmfaEB45mXAnnpL2YI9Im/lwbN4V7jeSMTHxOiWfhbFgIpkyl8/OTcAO9vn1zzIZikh5AkAoU6cnsK0Tpv353sFkNHjyAPCPOWxV1jyoKYnV1Qf8jt2GzUjWPL8pBbWNogQtRSFC+5ixly8e/txJQdGpYrrk";
	String merchantCode = request.getParameter("merchantCode").toString();
	String orderNo = request.getParameter("orderNo").toString();
	String signType = request.getParameter("signType").toString();
	
	String signDataSrc = "merchantCode="+merchantCode+"&orderNo="+orderNo;
	System.out.println("sign original string："+signDataSrc);
	
	String sign = SignUtils.Signaturer(signDataSrc, privateKeyStr);
%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Order Info</title>
</head>
<body>
	<form action="/pay/queryPayOrder.do" method="post">
		<table>
			<tr>
				<td>Merchant Code:</td>
				<td><input name="merchantCode" id="merchantCode" type="text" value="<%=merchantCode %>"/></td>
			</tr>
			<tr>
				<td>Order No:</td>
				<td><input name="orderNo" id="orderNo" type="text" value="<%=orderNo %>"/></td>
			</tr>
			<tr>
				<td>Sign Type:</td>
				<td><input name="signType" id="signType" type="text" value="<%=signType %>"/></td>
			</tr>
			<tr>
				<td>Sign:</td>
				<td>
				<textarea cols="100" rows="5" name="sign" id="sign" ><%=sign%></textarea>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Submit"/></td>
				
			</tr>
		</table>
	</form>
</body>
</html>