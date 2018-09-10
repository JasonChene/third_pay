<!--#include file="asp_md5.asp"-->
<!--#include file="merChat.asp"-->
<%
'***************************************联瑞支付点卡消费接口

'***************************************制作时间：2014-3-29

'***************************************制作人：Mr.Zhang
response.Charset="gb2312"
on error resume next'''HTTP异步传输函数
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


dim cardType'卡类型

dim parter'商户ID

dim cardNo'卡号

dim cardPwd'卡密

dim values'提交金额

dim restrict'使用范围

dim orderid'商户定单号

dim callbackurl'与支付平台下行通知商户地址

dim userkey'支付分配给商户密钥

cardType = Trim(request("rad"))'点卡类型支付消费接口文档上有说明

cardNo = Trim(request("cardNo"))'卡号

cardPwd = Trim(request("cardPwd"))'卡密

price = Trim(request("Price"))'充值金额

restrict = Trim(request("restrict"))'使用范围，仅限制于神州行充值卡

userkey = u_userkey'商户密钥，用户自行替换

parter = u_parter'测试商户ID，用户自行替换

callbackurl = u_callbackurl


'判断参数是否有效
if cardType="" or parter="" or cardNo="" or cardPwd="" or price="" or  restrict="" or  callbackurl="" or userkey="" then
	response.Write "参数不正确"
	response.End()
end if

Randomize 
rnds = Int((900 * Rnd) + 100)
orderid = year(now())&month(now())&day(now())&hour(now())&minute(now())&second(now())&rnds'生成商户订单号,商户可自行定义

sendurl = u_sendurl'提交到支付接口的地址

dim sign'参数进行加密签名
'=====================加密说明==========================
'参数加密的规则

'**********需要进行加密参数为:
'**********parter----商户ID
'**********cardType-----卡号类型
'**********cardNo-----卡号
'**********cardPwd-----卡密
'**********orderid-----订单号
'**********callbackurl-------支付回调页面
'**********restrict----------使用返回
'**********price------------提交金额

'加密格式必须如下所示：
'("parter="&xx&"&cardType="&xx&"&cardNo="&xx&"&cardPwd="&xx&"&orderid="&xx&"callbackurl="&xx&"&restrict="&xx&"&price="&xx&userkey)
'xx表示实际参数，请商户自行替换
'MD5加密必须是由国际标准的MD5加密函数进行加密,在加密前请对以下字符串进行加密
'加密测试字符串：1234567890abcdefghijklmnopqrstuvwxyz，若加密之后为：928f7bcdcd08869cc44c1bf24e7abec6则表示MD5加密正常,注：该加密结果值实际为36524支付接口服务器端MD5算法加密后结果。
'在加密参数中，需把商户密钥至于加密参数末尾
sign=asp_md5("type="&cardType&"&parter="&parter&"&cardno="&cardno&"&cardpwd="&cardpwd&"&value="&price&"&restrict="&restrict&"&orderid="&orderid&"&callbackurl="&callbackurl&userkey)
'==========================================================

'************************实际提交到誉满支付接口的参数
send_ekurl = sendurl & "?type="&cardType&"&parter="&parter&"&cardno="&cardno&"&cardpwd="&cardpwd&"&value="&price&"&restrict="&restrict&"&orderid="&orderid&"&callbackurl="&callbackurl&"&sign="&sign
'***********************将支付信息写入订单表

'商户写入自己平台的逻辑

'**************************

resultStr=xmlGet(send_ekurl)'调用HTTP函数,并抓取支付接口的通知信息


resultStr=right(resultStr,1)

select case trim(resultStr)
	case "0" 
		'提交成功以后。跳转到显示支付状态的页面
		response.write "提交成功，联瑞已经记录该卡"
		response.end()
	case "1"
		'参数无效直接通知商户，不在进入下行逻辑
		response.Write "参数无效,请检查之后重新提交"
	case "2"
		'签名验证无效，不在进入下行逻辑
		response.Write "签名验证无效"
	case "3"
		'卡密为重复提交
		response.Write "卡密为重复提交"
	case "4"
		'卡面值不符合规则
		response.Write "卡面值不符合规则"
	case else
		'未知的网络原因
		response.Write "接口维护中，请稍后再次提交"

end select
response.end()
%>