<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.obaopay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>����֧��֧������ӿ���ʾ(����-����)</title>
</head>
<body>
<%
/*
*�̻�ID����Կ��֧���ӿڵ�ַ �̼�Ӧ�����Լ��������ParterInfo.properties���޸�
*/
//�̻�ID
String parter = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("parter"));	
//��Կ
String md5key = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("key"));	
//֧���ӿڵ�ַ
String api_url = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("card_url")); 	
/*
* ��������
*/
String callbackurl = StringUtils.formatString(request.getParameter("callbackurl"));			//֧��������ص�ַ
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  			//����ID
String cardno = 	StringUtils.formatString(request.getParameter("cardno"));            	//֧��������
String cardpwd = 	StringUtils.formatString(request.getParameter("cardpwd"));           	//֧��������
String type = 		StringUtils.formatString(request.getParameter("type"));                	//֧��������
String value = 		StringUtils.formatString(request.getParameter("value"));               	//֧����ֵ
String attach = 	new String(StringUtils.formatString(request.getParameter("attach")).
		getBytes("iso-8859-1"),"gb2312");													//��ע��Ϣ		
String restrict = "0";																		//֧����ʹ����������,Ĭ��Ϊ0
String sign = obaopayEncrypt.obaopayCardMd5Sign(type,parter,cardno,cardpwd,value,restrict,    //ǩ��
		orderid,callbackurl,md5key);

/*
* �������ύ������֧��֧�����õ����н��
*/
String url =  api_url + "?type="+type+"&parter="+parter+"&cardno="+cardno+"&cardpwd="+cardpwd+"&value="+value
		+"&restrict="+restrict+"&orderid="+orderid+"&callbackurl="+callbackurl+"&sign="+sign+"&attach="+attach;
String result = HttpUtil.get(url);

//������֧��֧�����н��ת��Ϊ��Ӧ����˵��
String strResult = obaopayTypeConvert.opstateValueToChn(result); 
//��������ת��Ϊ����˵��
String chnType = obaopayTypeConvert.cardTypeToChn(type); 

%>
	<div>����֧��֧������ӿ���ʾ(����)</div>
			<div>������:<%=chnType%></div>
			<div>����:<%=cardno%></div>
			<div>����:<%=cardpwd%></div>
			<div>��ֵ:<%=value%></div>
			<div>�̻���ע��Ϣ:<%=attach%></div>
			<div>�����<%=strResult%></div>
</body>
</html>
