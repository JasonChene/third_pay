<!--#include file="asp_md5.asp"-->
<!--#include file="merchant.asp"-->
<%
dim orderid
'֧��������
dim restate
'����ֵ
dim ovalue
'�ύʵ�ʽ��
dim sign
'ǩ��
orderid=Trim(request("orderid"))
restate=Trim(request("restate"))
ovalue=Trim(request("ovalue"))
sign=Trim(request("sign"))
userkey = u_userkey
'�̻���Կ���û������滻
if orderid="" or Restate="" or ovalue="" or sign="" then
	response.Write("��������")
	response.End()
end if
signtxt = "orderid="&orderid&"&restate="&restate&"&ovalue="&ovalue&userkey
'ǩ���ַ���

mysign=asp_md5(signtxt)
if mysign <> sign then
'��֤ǩ���Ƿ���ȷ
	response.Write "ǩ������ȷ"
	response.End()
end if
dim resultstat
resultstat = "-999"
select case trim(Restate)
	case "0"'��ʾ֧���ɹ�
		'+++++++++++++++++++++++++++
		'�̻��ڴ˿��Դ����Լ����߼�
		'+++++++++++++++++++++++++++
		esultstr="ok"
		resultstat=0
	case "-1"
		esultstr="ok"
		resultstat=-1
	case "-2"
		esultstr="ok"
		resultstat=-2
	case "-3"'��ֵ����
		'+++++++++++++++++++++++++++
		'�̻��ڴ˿��Դ����Լ����߼�
		'+++++++++++++++++++++++++++
		esultstr="ok"
		resultstat=-3
	case "-4"
		esultstr="ok"
		resultstat=-4
	case "-5"
		esultstr="ok"
		resultstat=-5
	case else
		esultstr="err"
		resultstat=-6
end select
response.Write esultstr
'�̻�ϵͳ�յ�֪ͨ�Ժ������ Сд"ok"��ʾ����֪ͨ�ɹ�
response.End()
%>