<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.lianruipaypay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>����֧�������ӿ���ʾ(�����첽֪ͨ)</title>
</head>
<body>
<%
/*
* ��Կ�̼�Ӧ�����Լ��������ParterInfo.properties���޸�
*/
String md5key = 	StringUtils.formatString(lianruipaypayConfig.getInstance().getValue("key"));			//��Կ
/*
* ��������
*/
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  					//����ID
String opstate = 	StringUtils.formatString(request.getParameter("opstate"));            	 		//֧�����
String ovalue = 	StringUtils.formatString(request.getParameter("ovalue"));           			//֧�����
String sign = 		StringUtils.formatString(request.getParameter("sign"));                	 		//ǩ��
String sysorderid = 		StringUtils.formatString(request.getParameter("sysorderid"));           //���𶩵���
String completiontime = 		StringUtils.formatString(request.getParameter("completiontime"));               	//���𶩵�ʱ��
String attach = 		StringUtils.formatString(request.getParameter("attach"));               	//��ע��Ϣ�������ύ����ԭ������
String msg = 		StringUtils.formatString(request.getParameter("msg"));               			//֧���������˵��

if(!StringUtils.hasText(orderid) || !StringUtils.hasText(opstate) ||
		!StringUtils.hasText(ovalue) || !StringUtils.hasText(sign)){
	//������������ֱ�ӷ��ظ�֪����ӿ�
	out.println("opstate=-1");
	return;
}
String checksign = lianruipaypayEncrypt.lianruipaypayCardBackMd5Sign(orderid,opstate,ovalue,md5key);
if(checksign.equals(sign)){
	//�����Լ�ǩ����֤ͨ�����Զ������д���
	if(opstate.equals("0")){
		//����֧���ɹ���ʵ��֧������ovalue�л�ȡ��ovalue��λԪ
		//����ҽ��г�ֵ������������Ȳ��������������
	}else{
		//����֧��ʧ�ܣ�ʧ��ԭ��ɴ�msg�л�ȡ
	}
	//��֪����ӿ��Ѿ��յ��������Ľ��
	out.println("opstate=0");
}else{
	//ǩ������,ֱ�ӷ��ظ�֪����ӿ�
	out.println("opstate=-2");
}
%>
</body>
</html>