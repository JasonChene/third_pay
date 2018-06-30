<!--#include file="pay_config.asp"-->
<!--#include file="md5.inc"-->
<%

Dim paytype,paymoney,ordernumber,attach

ordernumber=Request.Form("ordernumber")
paytype=Request.Form("paytype")
paymoney=Request.Form("paymoney")
attach=Request.Form("attach")

signSource = "appid="&appid&"&paytype="&paytype&"&paymoney="&paymoney&"&ordernumber="&ordernumber&"&callbackurl="&notify_url&""   '串连拼接数据

sn=signSource&key

sign=MD5(sn)
'Response.Write(signSource)  
'Response.Write(sign)  
Response.Redirect(apiurl+"?"+signSource+"&attach="&attach&"&sign="+sign)

%>
