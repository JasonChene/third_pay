<!--#include file="asp_md5.asp"-->
<!--#include file="merChat.asp"-->
<%
'***************************************联瑞支付点卡消费接口

'***************************************制作时间：2016-3-29

'***************************************制作人：彭

dim orderid,opstate,sign,ovalue,userkey 
userkey = u_userkey'商户密钥
orderid = trim(request("orderid")) '商户订单号
opstate = trim(request("opstate")) '联瑞返回商户状态
ovalue = trim(request("ovalue")) '联瑞返回实际面值
sign = trim(request("sign")) '签名
resulttime = trim(request("systime")) '联瑞处理完成时间

'对下行参数进行签名验证
signu = asp_md5("orderid="&orderid&"&opstate="&opstate&"&ovalue="&ovalue&userkey)

if signu<>sign then'验证失败
	response.Write "签名错误"
	response.End()
end if

'opstate参数状态说明
'opstate = 0,卡被成功使用
'opstate = -1,卡号密码错误
'opstate = -2,卡实际面值和提交时面值不符，卡内实际面值未使用
'opstate = -3,实际面值和提交时面值不符，卡内实际面值已被使用。卡实际面值由ovalue表示
'opstate = -4,卡已经使用（卡在提交到联瑞联盟之前已经被使用）
if cint(opstate) = 0 then
'支付成功，可进行商户自身逻辑处理
	response.Write "opstate=0"'商户接收到联瑞的通知以后需要在页面上输出opstate=0，表示接收到通知，联瑞不会在继续进行通知了
	response.End()
else
'支付失败，可进行商户自身逻辑处理
	response.Write "opstate=0"'商户接收到联瑞的通知以后需要在页面上输出opstate=0，表示接收到通知，联瑞不会在继续进行通知了
	response.End()
end if
%>