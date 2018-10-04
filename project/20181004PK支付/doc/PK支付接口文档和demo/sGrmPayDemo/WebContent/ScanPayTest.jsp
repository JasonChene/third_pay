<%@ page language="java" contentType="text/html; charset=UTF-8"%>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>支付测试Demo</title>
</head>
<body>

	<h3 align="center">扫码支付测试</h3>
	<br />
	<form action="scanPaySubmit2.jsp" method="post">
	    <input type="hidden" name="merId" value="100000020000200">
		<input type="hidden" name="version" value="1.0.0">
	    <input type="hidden" name="merOrderNo" value="<%=System.currentTimeMillis()+"" %>">
		<input type="hidden" name="orderTitle" value="支付测试demo">
		<input type="hidden" name="orderDesc" value="描述">
		<input type="hidden" name="notifyUrl" value="http://120.79.216.35/grmApp/testMerNotify.do">
		<input name="mercKey" type="hidden" value="357199d5cc2b482dc59bebea8f8273cf"/>
		<input name="reqUrl" type="hidden" value="http://120.79.216.35/grmApp/createScanOrder.do"/>
		<table align="center">
			<tr>
				<td>请选择金额</td>
				<td>
				    <select name="orderAmt">
				      <option value="1">1元</option>
				      <option value="2">2元</option>
				      <option value="3">3元</option>
				      <option value="4">4元</option>
				      <option value="5">5元</option>
				    </select>
				</td>
			</tr>
			<tr>
				<td>请选择支付平台</td>
				<td>
				   <select name="payPlat">
				      <option value="wxpay">微信支付</option>
				      <option value="alipay">支付宝</option>
				   </select>
				</td>
			</tr>
			
			<tr>
				<td></td>
				<td><b><button type="submit">提交</button></b></td>
			</tr>
		</table>
	</form>
</body>
</html>