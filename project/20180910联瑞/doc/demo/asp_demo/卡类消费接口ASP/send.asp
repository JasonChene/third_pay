<!--#include file="asp_md5.asp"-->
<!--#include file="merChat.asp"-->
<%
'***************************************����֧���㿨���ѽӿ�

'***************************************����ʱ�䣺2014-3-29

'***************************************�����ˣ�Mr.Zhang
response.Charset="gb2312"
on error resume next'''HTTP�첽���亯��
Function xmlGet(url)
	set objXML = server.CreateObject("MSXML2.ServerXMLHTTP.3.0")
	objXML.open "GET", url, false
	objXML.setRequestHeader "Accept","image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/x-shockwave-flash, */*"
	objXML.setRequestHeader "Accept-Language","zh-cn"
	objXML.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
	objXML.setRequestHeader "Accept-Encoding", "gzip, deflate"
	objXML.setRequestHeader "User-Agent","Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)"
	objXML.setRequestHeader "Connection","Keep-Alive" 
	objXML.setRequestHeader "Cache-Control","no-cache"
	objXML.send()
	if objXML.readystate<>4 then
		exit function
	end if
	set oStream = server.CreateObject("ADODB.Stream")
	oStream.Type=1
	oStream.Mode=3
	oStream.Open()
	oStream.Write objXML.responseBody
	oStream.Position= 0
	oStream.Type= 2
	oStream.Charset="gb2312"
	ReturnText = oStream.readtext()
	oStream.Close()
	set oStream = nothing
	set objXML = nothing
	xmlGet = ReturnText	
End function


dim cardType'������

dim parter'�̻�ID

dim cardNo'����

dim cardPwd'����

dim values'�ύ���

dim restrict'ʹ�÷�Χ

dim orderid'�̻�������

dim callbackurl'��֧��ƽ̨����֪ͨ�̻���ַ

dim userkey'֧��������̻���Կ

cardType = Trim(request("rad"))'�㿨����֧�����ѽӿ��ĵ�����˵��

cardNo = Trim(request("cardNo"))'����

cardPwd = Trim(request("cardPwd"))'����

price = Trim(request("Price"))'��ֵ���

restrict = Trim(request("restrict"))'ʹ�÷�Χ���������������г�ֵ��

userkey = u_userkey'�̻���Կ���û������滻

parter = u_parter'�����̻�ID���û������滻

callbackurl = u_callbackurl


'�жϲ����Ƿ���Ч
if cardType="" or parter="" or cardNo="" or cardPwd="" or price="" or  restrict="" or  callbackurl="" or userkey="" then
	response.Write "��������ȷ"
	response.End()
end if

Randomize 
rnds = Int((900 * Rnd) + 100)
orderid = year(now())&month(now())&day(now())&hour(now())&minute(now())&second(now())&rnds'�����̻�������,�̻������ж���

sendurl = u_sendurl'�ύ��֧���ӿڵĵ�ַ

dim sign'�������м���ǩ��
'=====================����˵��==========================
'�������ܵĹ���

'**********��Ҫ���м��ܲ���Ϊ:
'**********parter----�̻�ID
'**********cardType-----��������
'**********cardNo-----����
'**********cardPwd-----����
'**********orderid-----������
'**********callbackurl-------֧���ص�ҳ��
'**********restrict----------ʹ�÷���
'**********price------------�ύ���

'���ܸ�ʽ����������ʾ��
'("parter="&xx&"&cardType="&xx&"&cardNo="&xx&"&cardPwd="&xx&"&orderid="&xx&"callbackurl="&xx&"&restrict="&xx&"&price="&xx&userkey)
'xx��ʾʵ�ʲ��������̻������滻
'MD5���ܱ������ɹ��ʱ�׼��MD5���ܺ������м���,�ڼ���ǰ��������ַ������м���
'���ܲ����ַ�����1234567890abcdefghijklmnopqrstuvwxyz��������֮��Ϊ��928f7bcdcd08869cc44c1bf24e7abec6���ʾMD5��������,ע���ü��ܽ��ֵʵ��Ϊ36524֧���ӿڷ�������MD5�㷨���ܺ�����
'�ڼ��ܲ����У�����̻���Կ���ڼ��ܲ���ĩβ
sign=asp_md5("type="&cardType&"&parter="&parter&"&cardno="&cardno&"&cardpwd="&cardpwd&"&value="&price&"&restrict="&restrict&"&orderid="&orderid&"&callbackurl="&callbackurl&userkey)
'==========================================================

'************************ʵ���ύ������֧���ӿڵĲ���
send_ekurl = sendurl & "?type="&cardType&"&parter="&parter&"&cardno="&cardno&"&cardpwd="&cardpwd&"&value="&price&"&restrict="&restrict&"&orderid="&orderid&"&callbackurl="&callbackurl&"&sign="&sign
'***********************��֧����Ϣд�붩����

'�̻�д���Լ�ƽ̨���߼�

'**************************

resultStr=xmlGet(send_ekurl)'����HTTP����,��ץȡ֧���ӿڵ�֪ͨ��Ϣ


resultStr=right(resultStr,1)

select case trim(resultStr)
	case "0" 
		'�ύ�ɹ��Ժ���ת����ʾ֧��״̬��ҳ��
		response.write "�ύ�ɹ��������Ѿ���¼�ÿ�"
		response.end()
	case "1"
		'������Чֱ��֪ͨ�̻������ڽ��������߼�
		response.Write "������Ч,����֮�������ύ"
	case "2"
		'ǩ����֤��Ч�����ڽ��������߼�
		response.Write "ǩ����֤��Ч"
	case "3"
		'����Ϊ�ظ��ύ
		response.Write "����Ϊ�ظ��ύ"
	case "4"
		'����ֵ�����Ϲ���
		response.Write "����ֵ�����Ϲ���"
	case else
		'δ֪������ԭ��
		response.Write "�ӿ�ά���У����Ժ��ٴ��ύ"

end select
response.end()
%>