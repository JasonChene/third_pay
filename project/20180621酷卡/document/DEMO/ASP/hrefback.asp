<!--#include file="asp_md5.asp"-->
<!--#include file="merChat.asp"-->
<%
dim orderid,opstate,sign,ovalue,userkey 
userkey = u_userkey'�̻���Կ
orderid = trim(request("orderid")) '�̻�������
opstate = trim(request("opstate")) '�����̻�״̬
ovalue = trim(request("ovalue")) '����ʵ����ֵ
sign = trim(request("sign")) 'ǩ��
resulttime = trim(request("resulttime")) '�������ʱ��

'�����в�������ǩ����֤
signu = asp_md5("orderid="&orderid&"&opstate="&opstate&"&ovalue="&ovalue&userkey)

if signu<>sign then'��֤ʧ��
	response.Write "ǩ������"
	response.End()
end if

if cint(opstate) = 0 then
'֧���ɹ����ɽ����̻������߼�����
	msg = "��ֵ�ɹ�:��ֵ���:"&ovalue&",������:"&orderid&""
	response.Write "opstate=0"'�̻����յ���֪ͨ�Ժ���Ҫ��ҳ�������opstate=0����ʾ���յ�֪ͨ�������ڼ�������֪ͨ��
	response.End()
else
'֧��ʧ�ܣ��ɽ����̻������߼�����
	response.Write "opstate=0"'�̻����յ���֪ͨ�Ժ���Ҫ��ҳ�������opstate=0����ʾ���յ�֪ͨ�������ڼ�������֪ͨ��
	response.End()
end if
%>