<%@ page language="java" contentType="text/html; charset=GB2312"  
import="com.lianruipaypay.util.*" pageEncoding="GB2312"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>����֧������ӿ���ʾ(�࿨-����)</title>
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
String api_url = 	StringUtils.formatString(lianruipaypayConfig.getInstance().getValue("card_url")); 	
/*
* ��������
*/
String callbackurl = StringUtils.formatString(request.getParameter("callbackurl"));			//֧��������ص�ַ
String orderid = 	StringUtils.formatString(request.getParameter("orderid"));  			//����ID
String type = 		StringUtils.formatString(request.getParameter("type"));                	//֧��������
String attach = 	new String(StringUtils.formatString(request.getParameter("attach")).
		getBytes("iso-8859-1"),"gb2312");													//��ע��Ϣ		
String totalvalue = StringUtils.formatString(request.getParameter("totalvalue"));           //�ܽ��		

//��̬��֯���š����롢����ֵ����
String num = StringUtils.formatString(request.getParameter("num"));
String cardnos[] = request.getParameterValues("cardno");
String cardpwds[] = request.getParameterValues("cardpwd");
String values[] = request.getParameterValues("value");
Integer iNum = Integer.valueOf(num);
String cardno = "";					//���ſ���,�࿨��Ӣ�Ķ��ŷָ�
String cardpwd = "";				//��������,�࿨��Ӣ�Ķ��ŷָ�
String value = "";					//��ֵ,�࿨��Ӣ�Ķ��ŷָ�
String restrict = "";				//��ʹ������,�࿨��Ӣ�Ķ��ŷָ�
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
//��������ת��Ϊ����˵��
String chnType = lianruipaypayTypeConvert.cardTypeToChn(type); 

if(!Integer.valueOf(totalvalue).equals(sValue)){
	strResult = "�ύ�Ķ��ſ���ֵ������ֵ����ȣ����ֵ�ύ";
}else{
	String sign = lianruipaypayEncrypt.lianruipaypayCardMultiMd5Sign(type,parter,cardno,cardpwd,value,totalvalue,restrict,    //ǩ��
		orderid,attach,callbackurl,md5key);	
	/*
	* �������ύ������֧�����õ����н��
	*/
	String url =  api_url + "?type="+type+"&parter="+parter+"&cardno="+cardno+"&cardpwd="+cardpwd+"&value="+value
			+"&totalvalue="+totalvalue+"&restrict="+restrict+"&attach="+attach+"&orderid="+orderid+"&callbackurl="+callbackurl+"&sign="+sign;
	System.out.println(url);
	String result = HttpUtil.get(url);

	//������֧�����н��ת��Ϊ��Ӧ����˵��
	strResult = lianruipaypayTypeConvert.opstateValueToChn(result); 
}
%>
	<div>����֧������ӿ���ʾ(�࿨)</div>
			<div>������:<%=chnType%></div>
			<div>����:<%=cardno%></div>
			<div>����:<%=cardpwd%></div>
			<div>��ֵ:<%=value%></div>
			<div>�̻���ע��Ϣ:<%=attach%></div>
			<div>�����<%=strResult%></div>
</body>
</html>
