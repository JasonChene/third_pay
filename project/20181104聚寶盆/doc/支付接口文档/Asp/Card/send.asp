<!--#include file="asp_md5.asp"-->
<!--#include file="merchant.asp"-->
<%
dim cardType
'������
dim parter
'�̻�ID
dim cardNo
'����
dim cardPwd
'����
dim values
'�ύ���
dim restrict
'ʹ�÷�Χ
dim orderid
'�̻�������
dim callbackurl
'����֧��ƽ̨����֪ͨ�̻���ַ
dim userkey
'�Ƕ�Ӯ�Ƽ�֧��������̻���Կ
parter = u_parter
'�̻�ID
userkey = u_userkey
'�̻���Կ
cardtype = Trim(request("cardtype"))
'�㿨����
cardno = Trim(request("cardno"))
'����
cardpwd = Trim(request("cardpwd"))
'����
price = Trim(request("price2"))
'��ֵ���
restrict = 0
'ʹ�÷�Χ���������������г�ֵ��
orderid = Trim(request("num"))
if orderid = "" then
	Randomize 
	rnds = Int((900 * Rnd) + 100)
	orderid=year(now())&month(now())&day(now())&hour(now())&minute(now())&second(now())&rnds''''�����̻�������,�̻������ж���
end if
'�̻�ϵͳ������
userkey = u_userkey
'�̻���Կ���û������滻
parter = u_parter
'�����̻�ID���û������滻
callbackurl = u_callbackurl
'֧������ص���ַ
if restrict = "" or not(isnumeric(restrict)) then restrict = 0
'�жϲ����Ƿ���Ч
if  parter="" or cardno="" or cardpwd="" or price="" or  restrict="" or  callbackurl="" or userkey="" or orderid = "" then
	response.Write "��������ȷ"
	response.End()
end if
sendurl = u_sendurl
'�ύ���Ƕ�Ӯ�Ƽ�֧���㿨�ӿڵĵ�ַ
dim sign'�������м���ǩ��
signtxt = "parter="&parter&"&cardtype="&cardtype&"&cardno="&cardno&"&cardpwd="&cardpwd&"&orderid="&orderid&"&callbackurl="&callbackurl&"&restrict="&restrict&"&price="&price&userkey
sign=asp_md5(signtxt)
send_xyurl = sendurl&"?parter="&parter&"&cardtype="&cardtype&"&cardno="&cardNo&"&cardpwd="&cardPwd&"&orderid="&orderid&"&callbackurl="&callbackurl&"&restrict="&restrict&"&price="&price&"&sign="&sign
response.write(send_xyurl)
response.Redirect(send_xyurl)
response.End()
'++++++++++++++++++++++++++++
'�̻�д���Լ�ƽ̨���߼�
'++++++++++++++++++++++++++++
resultStr=xmlGet(send_xyurl)'�첽�ύ����������Ϣ
'resultStr=cstr(right(resultStr,1))
select case trim(resultStr)
	case "0" 
		'�ύ�ɹ��Ժ���ת����ʾ֧��״̬��ҳ��
		response.Write "ok"
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
		response.Write resultStr
end select
response.end()
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
%>