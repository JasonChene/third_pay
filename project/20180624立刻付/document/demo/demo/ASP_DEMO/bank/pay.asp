<!--#include file="pay_config.asp"-->
<!--#include file="md5.inc"-->
<%

Dim banktype,paymoney,ordernumber,attach

ordernumber=Request.Form("ordernumber")
banktype=Request.Form("banktype")
paymoney=Request.Form("paymoney")
attach=Request.Form("attach")

signSource = "partner="&partner&"&banktype="&banktype&"&paymoney="&paymoney&"&ordernumber="&ordernumber&"&callbackurl="&notify_url&""   '串连拼接数据

sn=signSource&key

sign=MD5(sn)
'Response.Write(signSource)  
'Response.Write(sign)  
Response.Redirect(apiurl+"?"+signSource+"&hrefbackurl="&return_url&"&attach="&attach&"&sign="+sign)

%>
