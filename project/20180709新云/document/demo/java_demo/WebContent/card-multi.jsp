<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.obaopay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>新云支付支付卡类接口演示(多卡-上行)</title>
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
String type = 		StringUtils.formatString(request.getParameter("type"));                	//支付卡类型
String attach = 	new String(StringUtils.formatString(request.getParameter("attach")).
		getBytes("iso-8859-1"),"gb2312");													//备注信息		
String totalvalue = StringUtils.formatString(request.getParameter("totalvalue"));           //总金额		

//动态组织卡号、密码、卡面值参数
String num = StringUtils.formatString(request.getParameter("num"));
String cardnos[] = request.getParameterValues("cardno");
String cardpwds[] = request.getParameterValues("cardpwd");
String values[] = request.getParameterValues("value");
Integer iNum = Integer.valueOf(num);
String cardno = "";					//多张卡号,多卡以英文逗号分割
String cardpwd = "";				//多张密码,多卡以英文逗号分割
String value = "";					//面值,多卡以英文逗号分割
String restrict = "";				//卡使用限制,多卡以英文逗号分割
Integer sValue = Integer.valueOf(0);

String strResult = "";
for(int i = 0; i < iNum; i++){
	sValue += Integer.valueOf(values[i]);
	if(i != iNum - 1){
		cardno += cardnos[i] + ",";
		cardpwd += cardpwds[i] + ",";
		value += values[i] + ",";
		restrict += "0,";		
	}else{
		cardno += cardnos[i];
		cardpwd += cardpwds[i];
		value += values[i];
		restrict += "0";
	}
}
//将卡类型转换为中文说明
String chnType = obaopayTypeConvert.cardTypeToChn(type); 

if(!Integer.valueOf(totalvalue).equals(sValue)){
	strResult = "提交的多张卡面值和总面值不相等，请充值提交";
}else{
	String sign = obaopayEncrypt.obaopayCardMultiMd5Sign(type,parter,cardno,cardpwd,value,totalvalue,restrict,    //签名
		orderid,attach,callbackurl,md5key);	
	/*
	* 将订单提交到新云支付支付并得到上行结果
	*/
	String url =  api_url + "?type="+type+"&parter="+parter+"&cardno="+cardno+"&cardpwd="+cardpwd+"&value="+value
			+"&totalvalue="+totalvalue+"&restrict="+restrict+"&attach="+attach+"&orderid="+orderid+"&callbackurl="+callbackurl+"&sign="+sign;
	System.out.println(url);
	String result = HttpUtil.get(url);

	//将新云支付支付上行结果转换为对应中文说明
	strResult = obaopayTypeConvert.opstateValueToChn(result); 
}
%>
	<div>新云支付支付卡类接口演示(多卡)</div>
			<div>卡类型:<%=chnType%></div>
			<div>卡号:<%=cardno%></div>
			<div>密码:<%=cardpwd%></div>
			<div>面值:<%=value%></div>
			<div>商户备注信息:<%=attach%></div>
			<div>结果：<%=strResult%></div>
</body>
</html>
