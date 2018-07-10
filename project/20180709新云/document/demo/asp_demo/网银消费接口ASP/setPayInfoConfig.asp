<%
Dim u_parter'商户ID

Dim u_userkey'通信密钥

Dim u_callbackurl'支付回调页面

Dim u_hrefbackurl'支付完成后跳转的页面

Dim u_sendurl'提交地址请替换

'商户ID，支付时请替换为正式的商户ID
u_parter = 123

'支付密钥，支付时请替换正式的密钥
u_userkey = "123"

' 支付回调页面
u_callbackurl = "http://"&Request.ServerVariables("SERVER_NAME")&"/callback.asp"

'支付完成后跳转的页面
u_hrefbackurl = "http://"&Request.ServerVariables("SERVER_NAME")&"/hrefback.asp"' 支付跳转回页面

'提交地址请替换
u_sendurl = "http://openapi.7t1.cn/bank/index.aspx"
%>