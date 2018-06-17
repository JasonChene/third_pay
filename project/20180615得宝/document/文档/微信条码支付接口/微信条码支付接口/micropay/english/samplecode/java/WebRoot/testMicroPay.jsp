<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
	<form method="post" action="MicroPay.jsp" target="_blank">
		<br>商 家 号(merchant_code)：<input Type="text" Name="merchant_code" value="1111110166"> *		
		<br>服务类型(service_type)：<input Type="text" Name="service_type" value="weixin_micropay"> *
		<br>服务器异步通知地址(notify_url)：<input Type="text" Name="notify_url" value="http://zhangl.imwork.net:47963/MicroPayDemo/Notify_Url.jsp"> *
		<br>接口版本(interface_version)：<input Type="text" Name="interface_version" value="V3.1"/> *
		<br>字符编码(input_charset)：
			<select name="input_charset">
				<option value="UTF-8">UTF-8</option>
				<option value="GBK">GBK</option>
			</select> *																											
		<br>签名方式(sign_type)：
			<select name="sign_type">
				<option value="RSA-S">RSA-S</option>
				<option value="RSA">RSA</option>
			</select> *	
		<br>页面跳转同步通知地址(return_url)：<input Type="text" Name="return_url" value="http://zhangl.imwork.net:47963/MicroPayDemo/Return_Url.jsp">
		<br>客户端IP(client_ip)：<input Type="text" Name="client_ip" value="192.168.1.100"> *			
		<br>商户订单号(order_no)：<input Type="text" Name="order_no" value="<%=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date())%>"> *																															
		<br>商户订单时间(order_time)：<input Type="text" Name="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>*		
		<br>商户订单金额(order_amount)：<input Type="text" Name="order_amount" value="0.01"> *				
		<br>商品名称(product_name)：<input Type="text" Name="product_name" value="iPhone"> *
		<br>商品数量(product_num)：<input Type="text" Name="product_num" value="5"> 
		<br>二维码内容(auth_code)：<input Type="text" Name="auth_code" value="xxxx"> *
		<br>订单是否允许重复提交(redo_flag)：
			<select name="redo_flag">
				<option value="1">否</option>
				<option value="0">是</option>
			</select> 
		<br>-------------------------------------------------------------
		<br><input Type="submit" Name="submit" value="提交支付参数">
	</form>
  </body>
</html>
