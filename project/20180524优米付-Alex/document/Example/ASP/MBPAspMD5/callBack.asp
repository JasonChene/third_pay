<!-- #include file="merchantCommon.asp" -->
<%
'***********************************************
'* @Description 支付系统产品通用支付接口范例
'* @Version 1.0
'***********************************************

Dim srcString
Dim checkRst
Dim checkRstString

Dim apiName
Dim notifyTime
Dim tradeAmt
Dim merchNo
Dim merchParam
Dim orderNo
Dim tradeDate
Dim accNo
Dim accDate
Dim orderStatus
Dim sigString
Dim notifyType

apiName = Request.Form("apiName")
notifyTime = Request.Form("notifyTime")
tradeAmt = Request.Form("tradeAmt")
merchNo = Request.Form("merchNo")
merchParam = Request.Form("merchParam")
orderNo = Request.Form("orderNo")
tradeDate = Request.Form("tradeDate")
accNo = Request.Form("accNo")
accDate = Request.Form("accDate")
orderStatus = Request.Form("orderStatus")
sigString = Request.Form("signMsg")
notifyType = Request.Form("notifyType")

srcString = "apiName="&apiName&"&notifyTime="&notifyTime&"&tradeAmt="&tradeAmt&"&merchNo="&merchNo&"&merchParam="&merchParam&"&orderNo="&orderNo&"&tradeDate="&tradeDate&"&accNo="&accNo&"&accDate="&accDate&"&orderStatus="&orderStatus
sigString = URLDecode(sigString)

chkResult = ChkSignString(srcString, sigString)
if (chkResult <> 0) then
    checkRstString = "验证签名失败：" & chkResult
else
    '只有支付成功，当前页面才会被支付系统调用
    '验签通过，表示当前请求来自支付系统，接下来应该处理支付成功后响应的业务逻辑
    '例如：减少库存，标记订单为已支付等
    checkRstString = "验证签名成功"
    
    '以下代码处理支付系统后台通知--服务器点对点通知
    if (notifyType <> 0) then
        Response.Write("SUCCESS")
        Response.Flush
        Response.End
    end if
end if
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>支付系统商户接口范例-支付结果</title>
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
                        <td colspan="2" bgcolor="#CEE7BD">支付系统订单支付接口演示：</td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;apiName</td>
                        <td align="left">&nbsp;&nbsp;<%= apiName %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;notifyTime</td>
                        <td align="left">&nbsp;&nbsp;<%= notifyTime %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;tradeAmt</td>
                        <td align="left">&nbsp;&nbsp;<%= tradeAmt %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;merchNo</td>
                        <td align="left">&nbsp;&nbsp;<%= merchNo %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;merchParam</td>
                        <td align="left">&nbsp;&nbsp;<%= merchParam %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;orderNo</td>
                        <td align="left">&nbsp;&nbsp;<%= orderNo %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;tradeDate</td>
                        <td align="left">&nbsp;&nbsp;<%= tradeDate %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;accNo</td>
                        <td align="left">&nbsp;&nbsp;<%= accNo %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;accDate</td>
                        <td align="left">&nbsp;&nbsp;<%= accDate %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;orderStatus</td>
                        <td align="left">&nbsp;&nbsp;<%= orderStatus %></td>
                    </tr>
                    <tr>
                        <td align="left" height="20">&nbsp;&nbsp;验签结果</td>
                        <td align="left">&nbsp;&nbsp;<%= checkRstString %></td>
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