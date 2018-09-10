<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.lianruipaypay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>联瑞支付卡类接口演示(单卡-下行)</title>
</head>
<body>
<%
/*
* 密钥商家应根据自己的情况在ParterInfo.properties中修改
*/
String md5key = 	StringUtils.formatString(lianruipaypayConfig.getInstance().getValue("key"));			//密钥
/*
* 订单参数
*/
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  					//订单ID
String opstate = 	StringUtils.formatString(request.getParameter("opstate"));            	 		//支付结果
String ovalue = 	StringUtils.formatString(request.getParameter("ovalue"));           			//支付金额
String sign = 		StringUtils.formatString(request.getParameter("sign"));                	 		//签名
String sysorderid = 		StringUtils.formatString(request.getParameter("sysorderid"));           //联瑞订单号
String completiontime = 		StringUtils.formatString(request.getParameter("completiontime"));               	//联瑞订单时间
String attach = 		StringUtils.formatString(request.getParameter("attach"));               	//备注信息，上行提交参数原样返回
String msg = 		StringUtils.formatString(request.getParameter("msg"));               			//支付结果中文说明

if(!StringUtils.hasText(orderid) || !StringUtils.hasText(opstate) ||
		!StringUtils.hasText(ovalue) || !StringUtils.hasText(sign)){
	//参数参数错误，直接返回告知联瑞接口
	out.println("opstate=-1");
	return;
}
String checksign = lianruipaypayEncrypt.lianruipaypayCardBackMd5Sign(orderid,opstate,ovalue,md5key);
if(checksign.equals(sign)){
	//签名验证通过，根据opstate结果对订单进行处理
	if(opstate.equals("0") || opstate.equals("-3")){
		//订单支付成功，实际支付金额从ovalue中获取，ovalue单位元
		//对玩家进行充值，增加玩家余额等操作可在这里进行
	}else{
		//订单支付失败，失败原因可从msg中获取
	}
	//告知联瑞接口已经收到了正常的结果
	out.println("opstate=0");
}else{
	//签名错误,直接返回告知联瑞接口
	out.println("opstate=-2");
}
%>
</body>
</html>