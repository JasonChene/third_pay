<!-- #include file="merchantCommon.asp" -->
<%
'***********************************************
'* @Description 支付系统产品通用支付接口范例
'* @Version 1.0
'***********************************************
Dim apiName
Dim apiVersion
Dim platformID
Dim merchNo
Dim orderNo
Dim tradeDate
Dim amt
Dim merchUrl
Dim merchParam
Dim tradeSummary
Dim bankCode

Dim srcMsg
Dim signMsg

apiName = apiname_pay
apiVersion = api_version
platformID = merchPlatformId
merchNo = merchAccNo
orderNo = request("orderNo")
tradeDate = request("tradeDate")
amt = request("amt")
merchUrl = callbackUrl
merchParam = Server.UrlEncode(request("merchParam"))
tradeSummary = request("tradeSummary")
bankCode = request("bankCode")

srcMsg = "apiName=" & apiName
srcMsg = srcMsg & "&apiVersion=" & apiVersion
srcMsg = srcMsg & "&platformID=" & platformID
srcMsg = srcMsg & "&merchNo=" & merchNo
srcMsg = srcMsg & "&orderNo=" & orderNo
srcMsg = srcMsg & "&tradeDate=" & tradeDate
srcMsg = srcMsg & "&amt=" & amt
srcMsg = srcMsg & "&merchUrl=" & merchUrl
srcMsg = srcMsg & "&merchParam=" & merchParam
srcMsg = srcMsg & "&tradeSummary=" & tradeSummary
Call logStr(logFileName, "pay-srcMsg", srcMsg)

signMsg = GetSignString(srcMsg)
Call logStr(logFileName, "pay-signMsg", signMsg)
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>To MobaoPay Page</title>
</head>
<body onLoad="document.MobaoPay.submit()">
	<form name="MobaoPay" action="<%=epayUrl%>" method="get" accept-charset="UTF-8" target="_self">
		<input type="hidden" name="apiName" value="<%=apiName%>">
		<input type="hidden" name="apiVersion" value="<%=apiVersion%>">
		<input type="hidden" name="platformID" value="<%=platformID%>">
		<input type="hidden" name="merchNo" value="<%=merchNo%>">
		<input type="hidden" name="orderNo" value="<%=orderNo%>">
		<input type="hidden" name="tradeDate" value="<%=tradeDate%>">
		<input type="hidden" name="amt" value="<%=amt%>">
		<input type="hidden" name="merchUrl" value="<%=merchUrl%>">
		<input type="hidden" name="merchParam" value="<%=merchParam%>">
		<input type="hidden" name="tradeSummary" value="<%=tradeSummary%>">
		<input type="hidden" name="signMsg" value="<%=signMsg%>">
		<input type="hidden" name="bankCode" value="<%=bankCode%>">
	</form>
</body>
</html>