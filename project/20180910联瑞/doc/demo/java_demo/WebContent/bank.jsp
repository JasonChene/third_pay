<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.lianruipaypay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>����֧�������ӿ���ʾ(����)</title>
</head>
<body>
<%
/*
*�̻�ID����Կ��֧���ӿڵ�ַ �̼�Ӧ�����Լ��������ParterInfo.properties���޸�
*/
//�̻�ID
String parter = 	StringUtils.formatString(lianruipaypayConfig.getInstance().getValue("parter"));		
//��Կ
String md5key = 	StringUtils.formatString(lianruipaypayConfig.getInstance().getValue("key"));	
//֧���ӿڵ�ַ
String api_url = 	StringUtils.formatString(lianruipaypayConfig.getInstance().getValue("bank_url")); 	
/*
* ��������
*/
String callbackurl = StringUtils.formatString(request.getParameter("callbackurl"));			//֧������첽��ַ
String hrefbackurl = StringUtils.formatString(request.getParameter("hrefbackurl"));			//֧�����ͬ����ַ
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  			//����ID
String type = 		StringUtils.formatString(request.getParameter("type"));                	//֧��������
String value = 		StringUtils.formatString(request.getParameter("value"));               	//֧����ֵ
String attach = 	new String(StringUtils.formatString(request.getParameter("attach")).
		getBytes("iso-8859-1"),"gb2312");													//��ע��Ϣ		         		
String sign = lianruipaypayEncrypt.lianruipaypayBankMd5Sign(type,parter,value,orderid,callbackurl,md5key);//ǩ��
String payerIp = request.getRemoteAddr();													//���ip
%>
	<div>����֧�������ӿ���ʾ(����)</div>
		<form name="lianruipaypay" action='<%=api_url%>' method='GET' target="_blank">
			<input type='hidden' name='parter'   value='<%=parter%>'>
			<input type='hidden' name='type' value='<%=type%>'>
			<input type='hidden' name='orderid' value='<%=orderid%>'>
			<input type='hidden' name='callbackurl'   value='<%=callbackurl%>'>
			<input type='hidden' name='hrefbackurl'   value='<%=hrefbackurl%>'>
			<input type='hidden' name='value'   value='<%=value%>'>
			<input type='hidden' name='attach'  value='<%=attach%>'>
			<input type='hidden' name='payerIp' value='<%=payerIp%>'>
			<input type='hidden' name='sign'   value='<%=sign%>'>
			<input type='submit' value="ȥ��ֵ"/>
		</form>
</body>
</html>
