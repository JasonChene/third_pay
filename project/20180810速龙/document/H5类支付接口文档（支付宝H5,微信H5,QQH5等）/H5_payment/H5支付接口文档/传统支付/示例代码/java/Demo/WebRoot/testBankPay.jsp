<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page import="java.util.*" %>
<%@ page import="java.text.SimpleDateFormat" %>

<html>
  <head>    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
	<form method="post" action="bankPay.jsp" target="_blank">
		<!--带*号为必选参数  -->
		<br>商 家 号(merchant_code)：<input Type="text" Name="merchant_code" value="199001002003"> *		
		<br>服务类型(service_type)：
		  <select Name="service_type" >*
		      <option value="h5_ali">h5_ali</option>
			  <option value="h5_wx">h5_wx</option>
			  <option value="h5_qq">h5_qq</option>
			  <option value="h5_jd">h5_jd</option>
		  </select>
		<br>接口版本(interface_version)：<input Type="text" Name="interface_version" value="V3.0"/> *
		<br>字符编码(input_charset)：
			<select name="input_charset">
				<option value="UTF-8">UTF-8</option>
				<option value="GBK">GBK</option>
			</select> *		
		<br>服务器异步通知地址(notify_url)：<input Type="text" Name="notify_url" value="http://15l0549c66.iask.in:45191/bankPay/offline_notify.jsp"> *																							
		<br>签名方式(sign_type)：
			<select name="sign_type">
				<option value="RSA-S">RSA-S</option>
				<option value="RSA">RSA</option>
			</select> *	
		<br>商户订单号(order_no)：<input Type="text" Name="order_no" value="<%=new SimpleDateFormat("yyyyMMddHHmmss").format(new Date())%>"> *																															
		<br>商户订单时间(order_time)：<input Type="text" Name="order_time" value="<%=new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date())%>"/>*		
		<br>商户订单金额(order_amount)：<input Type="text" Name="order_amount" value="0.1"> *				
		<br>商品名称(product_name)：<input Type="text" Name="product_name" value="test"> *
		<!--以下参数为可选参数  -->
		<br>页面跳转同步通知地址(return_url)：<input Type="text" Name="return_url" value="http://15l0549c66.iask.in:45191/bankPay/page_notify.jsp"> 
		<br>银行直连代码(bank_code)：<input Type="text" Name="bank_code" value=""> 
		<br>订单是否允许重复提交(redo_flag)：
			<select name="redo_flag">
				<option value="1">否</option>
				<option value="0">是</option>
				<option value="">为空</option>
			</select> 
		<br>商品编号(product_code)：<input Type="text" Name="product_code" value="">
		<br>商品数量(product_num)：<input Type="text" Name="product_num" value="">
		<br>商品描述(product_desc)：<input Type="text" Name="product_desc" value="">
		<br>支付类型(pay_type)：<input Type="text" Name="pay_type" value=""> 
		<br>客户端ip(client_ip)：<input Type="text" Name="client_ip" value=""> 
		<br>业务扩展参数(extend_param)：<input Type="text" Name="extend_param" value=""> 
		<br>回传参数(extra_return_param)：<input Type="text" Name="extra_return_param" value=""> 
		<br>商品展示URL(show_url)：<input Type="text" Name="show_url" value=""> 
		<br><input Type="submit" Name="submit" value="立即支付">
	</form>
  </body>
</html>
