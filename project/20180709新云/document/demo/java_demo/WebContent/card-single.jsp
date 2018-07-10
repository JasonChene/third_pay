<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.obaopay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>新云支付支付卡类接口演示(单卡-上行)</title>
</head>
<body>
<%
/*
*商户ID、密钥、支付接口地址 商家应根据自己的情况在ParterInfo.properties中修改
*/
//商户ID
String parter = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("parter"));	
//密钥
String md5key = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("key"));	
//支付接口地址
String api_url = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("card_url")); 	
/*
* 订单参数
*/
String callbackurl = StringUtils.formatString(request.getParameter("callbackurl"));			//支付结果返回地址
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  			//订单ID
String cardno = 	StringUtils.formatString(request.getParameter("cardno"));            	//支付卡卡号
String cardpwd = 	StringUtils.formatString(request.getParameter("cardpwd"));           	//支付卡密码
String type = 		StringUtils.formatString(request.getParameter("type"));                	//支付卡类型
String value = 		StringUtils.formatString(request.getParameter("value"));               	//支付面值
String attach = 	new String(StringUtils.formatString(request.getParameter("attach")).
		getBytes("iso-8859-1"),"gb2312");													//备注信息		
String restrict = "0";																		//支付卡使用区域限制,默认为0
String sign = obaopayEncrypt.obaopayCardMd5Sign(type,parter,cardno,cardpwd,value,restrict,    //签名
		orderid,callbackurl,md5key);

/*
* 将订单提交到新云支付支付并得到上行结果
*/
String url =  api_url + "?type="+type+"&parter="+parter+"&cardno="+cardno+"&cardpwd="+cardpwd+"&value="+value
		+"&restrict="+restrict+"&orderid="+orderid+"&callbackurl="+callbackurl+"&sign="+sign+"&attach="+attach;
String result = HttpUtil.get(url);

//将新云支付支付上行结果转换为对应中文说明
String strResult = obaopayTypeConvert.opstateValueToChn(result); 
//将卡类型转换为中文说明
String chnType = obaopayTypeConvert.cardTypeToChn(type); 

%>
	<div>新云支付支付卡类接口演示(单卡)</div>
			<div>卡类型:<%=chnType%></div>
			<div>卡号:<%=cardno%></div>
			<div>密码:<%=cardpwd%></div>
			<div>面值:<%=value%></div>
			<div>商户备注信息:<%=attach%></div>
			<div>结果：<%=strResult%></div>
</body>
</html>
