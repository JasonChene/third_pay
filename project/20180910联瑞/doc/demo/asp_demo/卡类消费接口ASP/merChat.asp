<%

'***************************************����֧���㿨���ѽӿ�

'***************************************����ʱ�䣺2017-1-12

'***************************************�����ˣ�Mr LEE


Dim u_parter'�̻�ID

Dim u_userkey'ͨ����Կ

Dim u_callbackurl'֧���ص�ҳ��

Dim u_hrefbackurl'֧����ɺ���ת��ҳ��

Dim u_sendurl'�ύ��ַ���滻

'�̻�ID��֧��ʱ���滻Ϊ��ʽ���̻�ID
u_parter = 1000

'֧����Կ��֧��ʱ���滻��ʽ����Կ
u_userkey = "1E6EEFE0-4E8F-430E-B153-A9DFD7312754"

' ֧���ص�ҳ��
u_callbackurl = "http://"&Request.ServerVariables("SERVER_NAME")&"/callback.asp"

'�ύ��ַ���滻
u_sendurl = "http://zf.lianruipay.com/CardReceive.ashx"
%>