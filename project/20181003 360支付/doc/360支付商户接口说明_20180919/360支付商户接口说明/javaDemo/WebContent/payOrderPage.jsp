<%@ page language="java" contentType="text/html; charset=UTF-8"%>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>接口测试Demo</title>
</head>
<body>

	<h3 align="center">支付下单</h3>

	<br />

	<form action="payOrderSubmit.jsp" method="post">
		<table align="center">
			<tr>
				<td>商户编号</td>
				<td><input type="text" name="merId" value="360888890000100">merId</td>
			</tr>
			<tr>
				<td>接口版本</td>
				<td><input type="text" name="version" value="1.0.0">version</td>
			</tr>
			<tr>
				<td>订单号</td>
				<td><input type="text" name="orderNo" value="<%=System.currentTimeMillis()+"" %>">orderNo</td>
			</tr>
			<tr>
				<td>订单金额(以元为单位)</td>
				<td><input type="text" name="orderAmt" value="1">orderAmt</td>
			</tr>
			<tr>
				<td>请选择支付通道</td>
				<td>
				   <select name="thirdChannel">
				      <option value="alipay">支付宝</option>
				      <option value="wxpay">微信支付</option>
				   </select>
				</td>
			</tr>
			<tr>
				<td>请选择支付产品</td>
				<td>
				   <select name="payprod">
				      <option value="11">11-扫码支付</option>
				      <option value="10">10-WAP支付(只在手机端才能调起支付宝)</option>
				   </select>
				</td>
			</tr>
			<tr>
				<td>备用字段1</td>
				<td><input type="text" name="remark1" value="备用字段1">remark1</td>
			</tr>
			<tr>
				<td>备用字段2</td>
				<td><input type="text" name="remark2" value="备用字段2">remark2</td>
			</tr>
			<tr>
				<td>后台通知</td>
				<td><input type="text" name="notifyUrl" value="https://pay.ntmbg.cn/codePay/wehfowefhmon.do">notifyUrl</td>
			</tr>
			<tr>
				<td>成功跳转页面</td>
				<td><input type="text" name="callbackUrl" value="https://pay.ntmbg.cn/codePay/wehfowefhmon.do">callbackUrl</td>
			</tr>
			<tr>
				<td>商户MD5签名Key</td>
				<td><textarea name="mercKey" >2eb9c4eff0f5bd44a04746a3a299e7fa</textarea></td>
			</tr>
			<tr>
				<td>提交URL</td>
				<td><textarea name="reqUrl" rows="1" cols="60">https://pay.ntmbg.cn/codePay/payOrder.do</textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><b><button type="submit">提交</button></b></td>
			</tr>
		</table>
	</form>
</body>
</html>