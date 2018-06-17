<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>  
  <body>
	<form method="post" action="WebDinpay.jsp" target="_blank">
		<br>接口版本(interface_version)：<input Type="text" Name="interface_version" value="V3.0"/> *		
		<br>服务类型(service_type)：<input Type="text" Name="service_type" value="express_web_sign_pay"> *				
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
		<br>服务器异步通知地址(notify_url)：<input Type="text" Name="notify_url" value="http://15l0549c66.iask.in:11999/Quick_Dinpay/Notify_Url.jsp"> *			
		<br>商户订单号(order_no)：<input Type="text" Name="order_no" value="<%=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date())%>"> *
		<br>商户订单金额(order_amount)：<input Type="text" Name="order_amount" value="0.01"> *		
		<br>商户订单时间(order_time)：<input Type="text" Name="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>*
		<br>手机号(mobile)：<input Type="text" Name="mobile" value="13265627543"> *			
		<br>银行代码(bank_code)：<input Type="text" Name="bank_code" value="ABC"> *
		<br>银行卡类型(card_type)：
			<select name="card_type">
				<option value="0">借记卡</option>
				<option value="1">信用卡</option>
			</select> *
		<br>商品名称(product_name)：<input Type="text" Name="product_name" value="iPhone"> *
		<br>银行卡账号(card_no)：<input Type="text" Name="card_no" value="6228480128396157173"> *
		<br>银行卡户名(card_name)：<input Type="text" Name="card_name" value="张龙"> *
		<br>身份证号(id_no)：<input Type="text" Name="id_no" value="44148119891010313X"> *
		<br>-------------------------------------------------------------
		<br><input Type="submit" Name="submit" value="提交网页支付参数">
	</form>
  </body>
</html>
