<%

'***********************************************
'* @Description 支付系统产品通用支付接口范例
'* @Version 1.0
'***********************************************

Dim mbpKey
Dim epayUrl
Dim callbackUrl
Dim merchPlatformId
Dim merchAccNo
Dim apiname_pay
Dim apiname_query
Dim apiname_refund
Dim api_version
Dim logName

'商户MD5密钥，切换到正式环境需要替换为正式密钥
mbpKey = "1df5769d24aa1625e0c33e18b6f3f601"
'商户用地址-正式
epayUrl = "http://cashier.youmifu.com/cgi-bin/netpayment/pay_gate.cgi"

'商户接受支付通知地址(商户自己系统的地址,必须是公网地址，否则无法收到支付结果通知)
callbackUrl = "http://localhost/MBPAspMD5/callBack.asp"
'商户平台号及商户帐号
merchPlatformId = "856086110012929"
merchAccNo = "856086110012929"
'日志文件名
logFileName = "856086110012929"

'以下配置项不需要修改
apiname_pay = "WEB_PAY_B2C"
apiname_query = "MOBO_TRAN_QUERY"
apiname_refund = "MOBO_TRAN_RETURN"
api_version = "1.0.0.0"

%>