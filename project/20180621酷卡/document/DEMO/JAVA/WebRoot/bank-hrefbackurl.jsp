<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.ekapay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>支付网银接口演示(下行同步通知)</title>
</head>
<body>
<%
/*
* 密钥商家应根据自己的情况在ParterInfo.properties中修改
*/
String md5key = 	StringUtils.formatString(EkaPayConfig.getInstance().getValue("key"));			//密钥
/*
* 订单参数
*/
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  					//订单ID
String opstate = 	StringUtils.formatString(request.getParameter("opstate"));            	 		//支付结果
String ovalue = 	StringUtils.formatString(request.getParameter("ovalue"));           			//支付金额
String sign = 		StringUtils.formatString(request.getParameter("sign"));                	 		//签名
String ekaorderid = 		StringUtils.formatString(request.getParameter("ekaorderid"));           //订单号
String ekatime = 		StringUtils.formatString(request.getParameter("ekatime"));               	//订单时间
String attach = 		StringUtils.formatString(request.getParameter("attach"));               	//备注信息，上行提交参数原样返回
String msg = 		StringUtils.formatString(request.getParameter("msg"));               			//支付结果中文说明

if(!StringUtils.hasText(orderid) || !StringUtils.hasText(opstate) ||
		!StringUtils.hasText(ovalue) || !StringUtils.hasText(sign)){
	//参数参数错误，直接返回告知接口
	out.println("opstate=-1");
	return;
}
String checksign = EkaPayEncrypt.EkaPayCardBackMd5Sign(orderid,opstate,ovalue,md5key);
if(checksign.equals(sign)){
	//参数以及签名验证通过，可对订单进行处理
	if(opstate.equals("0") || opstate.equals("-3")){
		out.println("支付成功,成功金额" + ovalue);
	}else{
		//订单支付失败，失败原因可从msg中获取
		out.println("支付失败");
	}
}else{
	//签名错误,直接返回告知接口
	out.println("参数返回无效，请联系商家");
}
%>
</body>
</html>