<%
Dim u_parter
'�̻�ID
Dim u_userkey
'ͨ����Կ
Dim u_callbackurl
'֧���ص�ҳ��
Dim u_hrefbackurl
'֧����ɺ���ת��ҳ��
Dim u_sendurl
'�ύ��ַ���滻
u_parter = "10031"
'�̻�ID��֧��ʱ���滻Ϊ��ʽ���̻�ID
u_userkey = "082F4C92E5F403EDC4F0D5F71ACAE0CA"
'��Կ��֧��ʱ���滻��ʽ����Կ
u_callbackurl = "http://"&request.ServerVariables("HTTP_HOST")&"/Order/callBack.asp"
' ֧���ص�ҳ��
u_hrefbackurl = "http://"&request.ServerVariables("HTTP_HOST")&"/Order/show_order.asp"
'֧����ɺ���ת��ҳ��
u_sendurl = "http://pay.rbzart.com/interface/chargebank.aspx"
'�ύ��ַ���滻
%>