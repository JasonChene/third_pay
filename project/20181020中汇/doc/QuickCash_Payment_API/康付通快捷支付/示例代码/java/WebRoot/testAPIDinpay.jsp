<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>  
  <body>
	<form method="post" action="APIDinpay.jsp" target="_blank">
		<br>接口版本(interface_version)：<input Type="text" Name="interface_version" value="V3.0"/> *		
		<br>服务类型(service_type)：<input Type="text" Name="service_type" value="express_sign_pay"> *
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
		<br>商 家 号(merchant_code)：<input Type="text" Name="merchant_code" value="108008008666"> *		
		<br>服务器异步通知地址(notify_url)：<input Type="text" Name="notify_url" value="http://15l0549c66.iask.in:11999/Quick_Dinpay/Notify_Url.jsp"> *			
		<br>商户订单号(order_no)：<input Type="text" Name="order_no" value="108820160408"> *
		<br>商户订单金额(order_amount)：<input Type="text" Name="order_amount" value="0.01"> *		
		<br>商户订单时间(order_time)：<input Type="text" Name="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>*
		<br>手机号(mobile)：<input Type="text" Name="mobile" value="13265627543"> *		
		<br>签约号(merchant_sign_id)：<input Type="text" Name="merchant_sign_id" value="8e8062fce7a47afb"> *				
		<br>短信验证码流水号(sms_trade_no)：<input Type="text" Name="sms_trade_no" value="123456"> *
		<br>短信验证码(sms_code)：<input Type="text" Name="sms_code" value="123456"> *
		<br>商品名称(product_name)：<input Type="text" Name="product_name" value="iPhone"> *
		<br>-------------------------------------------------------------
		<br><input Type="submit" Name="submit" value="提交快捷支付参数">
	</form>
  </body>
</html>
