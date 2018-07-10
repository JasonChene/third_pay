<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.obaopay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>新云支付支付卡类接口演示(多卡-下行)</title>
</head>
<body>
<%
/*
* 密钥商家应根据自己的情况在ParterInfo.properties中修改
*/
String md5key = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("key"));			//密钥
/*
* 订单参数
*/
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  					//订单ID
String cardno = 	StringUtils.formatString(request.getParameter("cardno"));  						//支付卡卡号,多卡以引文逗号分割
String opstate = 	StringUtils.formatString(request.getParameter("opstate"));            	 		//支付结果,多卡以引文逗号分割
String ovalue = 	StringUtils.formatString(request.getParameter("ovalue"));           			//支付金额,,多卡以引文逗号分割
String ototalvalue = 	StringUtils.formatString(request.getParameter("ototalvalue"));           	//支付成功总金额
String attach = 		StringUtils.formatString(request.getParameter("attach"));               	//备注信息，上行提交参数原样返回
String sign = 		StringUtils.formatString(request.getParameter("sign"));                	 		//签名
String sysorderid = 		StringUtils.formatString(request.getParameter("sysorderid"));           //新云支付订单号
String completiontime = 		StringUtils.formatString(request.getParameter("completiontime"));               	//新云支付订单时间
String msg = 		StringUtils.formatString(request.getParameter("msg"));               			//支付结果中文说明

if(!StringUtils.hasText(orderid) || !StringUtils.hasText(cardno) || !StringUtils.hasText(opstate) ||
		!StringUtils.hasText(ovalue) || !StringUtils.hasText(ototalvalue)  || !StringUtils.hasText(sign)){
	//参数参数错误，直接返回告知新云支付接口
	out.println("opstate=-1");
	return;
}
String checksign = obaopayEncrypt.obaopayCardMultiBackMd5Sign(orderid,cardno,opstate,ovalue,ototalvalue,attach,msg,md5key);
if(checksign.equals(sign)){
	//签名验证通过,分别得到每一个卡的结果
	String[] cardnos = StringUtils.strSplit(cardno,",");
	String[] opstates = StringUtils.strSplit(opstate,",");
	String[] values = StringUtils.strSplit(ovalue,",");
	String[] msgs = StringUtils.strSplit(msg,",");
	//可根据这些值得到每一个卡的结果
	//也可根据ototalvalue同商家系统内orderid对应订单的提交金额是否相同判断订单是否成功
	//告知新云支付接口已经收到了正常的结果
	out.println("opstate=0");
}else{
	//签名错误,直接返回告知新云支付接口
	out.println("opstate=-2");
}
%>
</body>
</html>