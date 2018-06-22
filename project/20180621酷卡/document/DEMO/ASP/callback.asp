<!--#include file="asp_md5.asp"-->
<!--#include file="merChat.asp"-->
<%
dim orderid,opstate,sign,ovalue,userkey 
userkey = u_userkey'商户密钥
orderid = trim(request("orderid")) '商户订单号
opstate = trim(request("opstate")) '返回商户状态
ovalue = trim(request("ovalue")) '返回实际面值
sign = trim(request("sign")) '签名
resulttime = trim(request("resulttime")) '处理完成时间

'对下行参数进行签名验证
signu = asp_md5("orderid="&orderid&"&opstate="&opstate&"&ovalue="&ovalue&userkey)

if signu<>sign then'验证失败
	response.Write "签名错误"
	response.End()
end if

if cint(opstate) = 0 then
'支付成功，可进行商户自身逻辑处理
	response.Write "opstate=0"'商户接收到的通知以后需要在页面上输出opstate=0，表示接收到通知，不会在继续进行通知了
	response.End()
else
'支付失败，可进行商户自身逻辑处理
	response.Write "opstate=0"'商户接收到的通知以后需要在页面上输出opstate=0，表示接收到通知，不会在继续进行通知了
	response.End()
end if
%>