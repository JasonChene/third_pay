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

Dim tempStr
Dim srcMsg
Dim signMsg

Dim sendMsg
Dim returnMsg
Dim checkMsg
Dim checkSing
Dim strTable

apiName = apiname_query
apiVersion = api_version
platformID = merchPlatformId
merchNo = merchAccNo
orderNo = request("orderNo")
tradeDate = request("tradeDate")
amt = request("amt")

srcMsg = "apiName=" & apiName
srcMsg = srcMsg & "&apiVersion=" & apiVersion
srcMsg = srcMsg & "&platformID=" & platformID
srcMsg = srcMsg & "&merchNo=" & merchNo
srcMsg = srcMsg & "&orderNo=" & orderNo
srcMsg = srcMsg & "&tradeDate=" & tradeDate
srcMsg = srcMsg & "&amt=" & amt
Call logStr(logFileName, "query-srcMsg", srcMsg)

tempStr = GetSignString(srcMsg)
signMsg = tempStr
Call logStr(logFileName, "query-signMsg", signMsg)

sendMsg =srcMsg & "&signMsg=" & Server.URLEncode(signMsg)
Call logStr(logFileName, "query-sendMsg", sendMsg)

'取得订单退款请求处理结果.
returnMsg = TranscateRequest(sendMsg)
checkMsg = ParseAndChkResp4Query(returnMsg)
checkSing = checkMsg(0)
strTable = checkMsg(1)
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>支付系统商户接口范例-查询</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="css/mobaopay.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #107929">
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
				
					<tr>
						<td height="30" colspan="2" bgcolor="#6BBE18"><span style="color: #FFFFFF"><a href="index.html">感谢您使用支付系统平台</a></span></td>
					</tr>
					<tr>
						<td colspan="2" bgcolor="#CEE7BD">支付系统订单查询请求接口演示：</td>
					</tr>
					<tr>
						<td align="left" height="20">&nbsp;&nbsp;订单查询请求处理结果</td>
						<td align="left"><table width="100%"><%= strTable %></table></td>
					</tr>
					<tr>
						<td align="left" height="20">&nbsp;&nbsp;订单查询请求验签结果</td>
						<td align="left">&nbsp;&nbsp;<%= checkSing %></td>
					</tr>
					<tr>
						<td height="5" bgcolor="#6BBE18" colspan="2"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>