<%
Dim u_parter'�̻�ID

Dim u_userkey'ͨ����Կ

Dim u_callbackurl'֧���ص�ҳ��

Dim u_hrefbackurl'֧����ɺ���ת��ҳ��

Dim u_sendurl'�ύ��ַ���滻

'�̻�ID��֧��ʱ���滻Ϊ��ʽ���̻�ID
u_parter = 800001806

'֧����Կ��֧��ʱ���滻��ʽ����Կ
u_userkey = "e5271f667c00368dac8807be4f38e2a5"

' ֧���ص�ҳ��
u_callbackurl = "http://"&Request.ServerVariables("SERVER_NAME")&"/callback.asp"

'֧����ɺ���ת��ҳ��
u_hrefbackurl = "http://"&Request.ServerVariables("SERVER_NAME")&"/hrefback.asp"' ֧����ת��ҳ��

'�ύ��ַ���滻
u_sendurl = "http://pay.gwwub.com/paybank.aspx"
%>