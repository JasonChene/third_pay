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
u_parter = "1"
'�̻�ID��֧��ʱ���滻Ϊ��ʽ���̻�ID
u_userkey = "F0FE05A733B07A225761CFED9528F664"
'��Կ��֧��ʱ���滻��ʽ����Կ
u_callbackurl = "http://"&request.ServerVariables("HTTP_HOST")&"/NetApi/Test/Card/callBack.asp"
'֧����ɺ���ת��ҳ��
u_sendurl = "http://pay.jf57.top/anterface/cardreceive.aspx"
'�ύ��ַ���滻
%>