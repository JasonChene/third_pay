<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.obaopay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>����֧��֧������ӿ���ʾ(�࿨-����)</title>
</head>
<body>
<%
/*
* ��Կ�̼�Ӧ�����Լ��������ParterInfo.properties���޸�
*/
String md5key = 	StringUtils.formatString(obaopayConfig.getInstance().getValue("key"));			//��Կ
/*
* ��������
*/
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  					//����ID
String cardno = 	StringUtils.formatString(request.getParameter("cardno"));  						//֧��������,�࿨�����Ķ��ŷָ�
String opstate = 	StringUtils.formatString(request.getParameter("opstate"));            	 		//֧�����,�࿨�����Ķ��ŷָ�
String ovalue = 	StringUtils.formatString(request.getParameter("ovalue"));           			//֧�����,,�࿨�����Ķ��ŷָ�
String ototalvalue = 	StringUtils.formatString(request.getParameter("ototalvalue"));           	//֧���ɹ��ܽ��
String attach = 		StringUtils.formatString(request.getParameter("attach"));               	//��ע��Ϣ�������ύ����ԭ������
String sign = 		StringUtils.formatString(request.getParameter("sign"));                	 		//ǩ��
String sysorderid = 		StringUtils.formatString(request.getParameter("sysorderid"));           //����֧��������
String completiontime = 		StringUtils.formatString(request.getParameter("completiontime"));               	//����֧������ʱ��
String msg = 		StringUtils.formatString(request.getParameter("msg"));               			//֧���������˵��

if(!StringUtils.hasText(orderid) || !StringUtils.hasText(cardno) || !StringUtils.hasText(opstate) ||
		!StringUtils.hasText(ovalue) || !StringUtils.hasText(ototalvalue)  || !StringUtils.hasText(sign)){
	//������������ֱ�ӷ��ظ�֪����֧���ӿ�
	out.println("opstate=-1");
	return;
}
String checksign = obaopayEncrypt.obaopayCardMultiBackMd5Sign(orderid,cardno,opstate,ovalue,ototalvalue,attach,msg,md5key);
if(checksign.equals(sign)){
	//ǩ����֤ͨ��,�ֱ�õ�ÿһ�����Ľ��
	String[] cardnos = StringUtils.strSplit(cardno,",");
	String[] opstates = StringUtils.strSplit(opstate,",");
	String[] values = StringUtils.strSplit(ovalue,",");
	String[] msgs = StringUtils.strSplit(msg,",");
	//�ɸ�����Щֵ�õ�ÿһ�����Ľ��
	//Ҳ�ɸ���ototalvalueͬ�̼�ϵͳ��orderid��Ӧ�������ύ����Ƿ���ͬ�ж϶����Ƿ�ɹ�
	//��֪����֧���ӿ��Ѿ��յ��������Ľ��
	out.println("opstate=0");
}else{
	//ǩ������,ֱ�ӷ��ظ�֪����֧���ӿ�
	out.println("opstate=-2");
}
%>
</body>
</html>