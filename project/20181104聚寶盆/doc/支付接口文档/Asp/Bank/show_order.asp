<!--#include file="asp_md5.asp"-->
<!--#include file="merchant.asp"-->
<%
dim orderid
'֧��������
dim Restate
'����ֵ
dim ovalue
'�ύʵ�ʽ��
dim sign
'ǩ��
orderid=Trim(request("orderid"))
restate=Trim(request("restate"))
ovalue=Trim(request("ovalue"))
sign=Trim(request("sign"))
'�̻���Կ���û������滻
if orderid="" or restate="" or ovalue="" or sign="" then
	response.Write("��������")
	response.End()
end if
mysign=asp_md5("orderid="&orderid&"&restate="&restate&"&ovalue="&ovalue&u_userkey)
if mysign <> Lcase(sign) then
'��֤ǩ���Ƿ���ȷ
	response.Write "ǩ������ȷ"
	response.Write mysign &"<br />"&sign
	response.End()
end if
dim msg
msg = ""
select case trim(Restate)
	case "0"'��ʾ֧���ɹ�
		'+++++++++++++++++++++++++++
		'�̻��ڴ˿��Դ����Լ����߼�
		'+++++++++++++++++++++++++++
		msg = "֧���ɹ�!<br>�ɹ���"&ovalue&"<br>������:"&orderid
	case else
		msg = "֧���ɹ�!<br>������:"&orderid
end select
response.Write msg
%>