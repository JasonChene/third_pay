<%@ page language="java" contentType="text/html; charset=UTF-8"%>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>接口测试Demo</title>
</head>
<body>

	<h3 align="center">WAP支付</h3>

	<br />

	<form action="wapPaySubmit.jsp" method="post">
		<table align="center">
			<tr>
				<td>商户编号</td>
				<td><input type="text" name="merId" value="100000010000015">merId</td>
			</tr>
			<tr>
				<td>接口版本</td>
				<td><input type="text" name="version" value="1.0.0">version</td>
			</tr>
			
			<tr>
				<td>订单编号</td>
				<td><input type="text" name="merOrderNo" value="<%=System.currentTimeMillis()+"" %>">merOrderNo</td>
			</tr>
			
			<tr>
				<td>订单金额(以元为单位)</td>
				<td><input type="text" name="orderAmt" value="1">orderAmt</td>
			</tr>
		
			<tr>
				<td>请选择支付平台 alipay:支付宝  wxpay:微信支付</td>
				<td>
				   <select name="payPlat">
				      <option value="alipay">支付宝</option>
				      <option value="wxpay">微信支付</option>
				   </select>
				</td>
			</tr>
			<tr>
				<td>订单标题</td>
				<td><input type="text" name="orderTitle" value="订单标题Test">orderTitle</td>
			</tr>
			<tr>
				<td>订单描述</td>
				<td><input type="text" name="orderDesc" value="1213123">orderDesc</td>
			</tr>
			
			<tr>
				<td>后台通知url</td>
				<td><input type="text" name="notifyUrl" value="http://api.szcsjn.cn/grmApp/testMerNotify.do">notifyUrl</td>
			</tr>
			<tr>
				<td>页面跳转url</td>
				<td><input type="text" name="callbackUrl" value="http://api.szcsjn.cn/grmApp/testMerNotify.do">callbackUrl</td>
			</tr>
			<tr>
				<td>商户MD5签名Key</td>
				<td><textarea name="mercKey" >f2d9e5e00e8e94dbb3f9f57e57ba5f3a</textarea></td>
			</tr>
			<tr>
				<td>提交URL</td>
				<td><textarea name="reqUrl" rows="1" cols="60">http://api.szcsjn.cn/grmApp/createScanOrder.do</textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><b><button type="submit">提交</button></b></td>
			</tr>
		</table>
	</form>
</body>
</html>