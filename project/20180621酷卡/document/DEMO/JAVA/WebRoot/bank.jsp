<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.ekapay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>支付网银接口演示(上行)</title>
</head>
<body>
<%
/*
*商户ID、密钥、支付接口地址 商家应根据自己的情况在ParterInfo.properties中修改
*/
//商户ID
String parter = 	StringUtils.formatString(EkaPayConfig.getInstance().getValue("parter"));		
//密钥
String md5key = 	StringUtils.formatString(EkaPayConfig.getInstance().getValue("key"));	
//支付接口地址
String api_url = 	StringUtils.formatString(EkaPayConfig.getInstance().getValue("bank_url")); 	
/*
* 订单参数
*/
String callbackurl = StringUtils.formatString(request.getParameter("callbackurl"));			//支付结果异步地址
String hrefbackurl = StringUtils.formatString(request.getParameter("hrefbackurl"));			//支付结果同步地址
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  			//订单ID
String type = 		StringUtils.formatString(request.getParameter("type"));                	//支付卡类型
String value = 		StringUtils.formatString(request.getParameter("value"));               	//支付面值
String attach = 	new String(StringUtils.formatString(request.getParameter("attach")).
		getBytes("iso-8859-1"),"gb2312");													//备注信息		         		
String sign = EkaPayEncrypt.EkaPayBankMd5Sign(type,parter,value,orderid,callbackurl,md5key);//签名
String payerIp = request.getRemoteAddr();													//玩家ip
%>
	<div>支付网银接口演示(上行)</div>
		<form name="ekapay" action='<%=api_url%>' method='GET' target="_blank">
			<input type='hidden' name='parter'   value='<%=parter%>'>
			<input type='hidden' name='type' value='<%=type%>'>
			<input type='hidden' name='orderid' value='<%=orderid%>'>
			<input type='hidden' name='callbackurl'   value='<%=callbackurl%>'>
			<input type='hidden' name='hrefbackurl'   value='<%=hrefbackurl%>'>
			<input type='hidden' name='value'   value='<%=value%>'>
			<input type='hidden' name='attach'  value='<%=attach%>'>
			<input type='hidden' name='payerIp' value='<%=payerIp%>'>
			<input type='hidden' name='sign'   value='<%=sign%>'>
			<input type='submit' value="去充值"/>
		</form>
</body>
</html>
