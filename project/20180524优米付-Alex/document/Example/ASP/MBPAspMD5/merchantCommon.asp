<%@ CODEPAGE=65001 %>  
<% Response.CodePage=65001%>  
<% Response.Charset="UTF-8" %>
<!-- #include file="merchantProperties.asp" -->
<!-- #include file="mbpMD5.asp" -->
<%
'***********************************************
'* @Description 支付系统产品通用支付接口范例
'* @Version 1.0
'***********************************************

Set MsXmlDom = Server.CreateObject("Microsoft.XMLDom")
MsXmlDom.Async = False

'生成输入字符串的签名
Function GetSignString(srcString)
    Dim signedStr
    signedStr = MD5(srcString & mbpKey)
    GetSignString = signedStr
End Function

'验证输入字符串签名是否正确：0-正确 其他-错误
Function ChkSignString(srcString, sigString)
    Dim chkResult
    
    Call logStr(logFileName, "chk_src", srcString)
    Call logStr(logFileName, "chk_sig", sigString)
    chkResult = StrComp(MD5(srcString & mbpKey), LCase(sigString), 1)
    ChkSignString = chkResult
End Function

'解析并验证支付通知
Function ParseAndChkRequest4Pay(requestStr)
    Dim srcString
    Dim sigString
    Dim splitString
    Dim offset
    Dim chkResult
    Dim returnMsg
    
    if (len(requestStr) = 0) then
        returnMsg = "通知请求为空"
    else
        splitString = "&signMsg="
        offset = InStr(requestStr, splitString)
        srcString = Left(requestStr, offset - 1)
        sigString = Right(requestStr, len(requestStr) - len(splitString) - offset + 1)
        if (len(srcString) = 0) or (len(sigString) = 0) then
            returnMsg = "拆分通知参数字符串失败"
        else
            srcString = URLDecode(srcString)
            sigString = URLDecode(sigString)
            chkResult = ChkSignString(srcString, sigString)
            if (chkResult <> 0) then
                returnMsg = "验证签名失败：" & chkResult
            else
                returnMsg = "验证签名成功"
            end if
        end if
    end if
        
    ParseAndChkRequest4Pay = returnMsg
End Function

'解析并验证查询响应XML
Function ParseAndChkResp4Query(respXml)
    Dim srcString
    Dim sigString
    Dim returnMsg(1)
    Dim chkResult
	Dim pageContent
    MsXmlDom.loadXml(respXml)
    
    if (MsXmlDom.parseError.errorCode <> 0) then
        returnMsg(0) = "解析响应XML失败"
    else
        Set nodeRespData = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData")
        Set nodeSign = MsXmlDom.documentElement.SelectSingleNode("//moboAccount/signMsg")
		
		srcString = nodeRespData.xml
        sigString = nodeSign.text
        
        chkResult = ChkSignString(srcString, sigString)
        if (chkResult <> 0) then
            returnMsg(0) = "验证签名失败：" & chkResult
        else
            returnMsg(0) = "验证签名成功"
        end if
		
		Set nodeRespCode = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/respCode")
        Set nodeRespDesc = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/respDesc")
        Set nodeAccDate = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/accDate")
        Set nodeAccNo = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/accNo")
        Set nodeStatus = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/Status")
		
		pageContent = "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;响应码</td><td align=""left"">&nbsp;&nbsp;" & nodeRespCode.text & "</td></tr>"
		pageContent = pageContent & "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;响应描述</td><td align=""left"">&nbsp;&nbsp;" & nodeRespDesc.text & "</td></tr>"
		if (nodeRespCode.text = "00") then
			pageContent = pageContent & "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;支付平台订单日期</td><td align=""left"">&nbsp;&nbsp;" & nodeAccDate.text & "</td></tr>"
			pageContent = pageContent & "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;支付平台订单号</td><td align=""left"">&nbsp;&nbsp;" & nodeAccNo.text & "</td></tr>"
			pageContent = pageContent & "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;支付状态</td><td align=""left"">&nbsp;&nbsp;" & nodeStatus.text & "</td></tr>"
		end if
        returnMsg(1) = pageContent
		
		Set nodeRespCode = Nothing
		Set nodeRespDesc = Nothing
		Set nodeAccDate = Nothing
		Set nodeAccNo = Nothing
		Set nodeStatus = Nothing
        Set nodeRespData = Nothing
        Set nodeSign = Nothing
    end if
    
    ParseAndChkResp4Query = returnMsg
End Function

'解析并验证退款响应XML
Function ParseAndChkResp4Refund(respXml)
    Dim srcString
    Dim sigString
    Dim returnMsg(1)
    Dim chkResult
	Dim pageContent
    MsXmlDom.loadXml(respXml)
    
    if (MsXmlDom.parseError.errorCode <> 0) then
        returnMsg(0) = "解析响应XML失败"
    else
        Set nodeRespData = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData")
        Set nodeSign = MsXmlDom.documentElement.SelectSingleNode("//moboAccount/signMsg")
        
        srcString = nodeRespData.xml
        sigString = nodeSign.text
        
        chkResult = ChkSignString(srcString, sigString)
        if (chkResult <> 0) then
            returnMsg(0) = "验证签名失败：" & chkResult
        else
            returnMsg(0) = "验证签名成功"
        end if
		
		Set nodeRespCode = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/respCode")
        Set nodeRespDesc = MsXmlDom.documentElement.selectSingleNode("//moboAccount/respData/respDesc")
        
		pageContent = "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;响应码</td><td align=""left"">&nbsp;&nbsp;" & nodeRespCode.text & "</td></tr>"
		pageContent = pageContent & "<tr><td align=""left"" width=""30%"">&nbsp;&nbsp;响应描述</td><td align=""left"">&nbsp;&nbsp;" & nodeRespDesc.text & "</td></tr>"
		returnMsg(1) = pageContent
		
		Set nodeRespCode = Nothing
		Set nodeRespDesc = Nothing
        Set nodeRespData = Nothing
        Set nodeSign = Nothing
    end if
    
    ParseAndChkResp4Refund = returnMsg
End Function

'向支付收银台发起请求并接受响应
Function TranscateRequest(sendString)
    Dim returnMsg
    
    Set objHttp = Server.CreateObject("MSXML2.ServerXMLHTTP")
	objHttp.setOption(2) = 13056
    objHttp.open "POST", epayUrl , False
    objHttp.setRequestHeader "Content-type", "application/x-www-form-urlencoded"
    objHttp.Send sendString
    
    if (objHttp.status <> 200 ) then
        Call logStr(logFileName, "Common-httpstatus", objHttp.status)
        returnMsg = "Response code = " & objHttp.status
    else
        returnMsg = objHttp.responseText
        Call logStr(logFileName, "Common-httpresponse", returnMsg)
    end if
    
    TranscateRequest = returnMsg
End Function

Function URLDecode(strURL)
    Dim I
    
    If InStr(strURL, "%") = 0 Then
        URLDecode = strURL
        Exit Function
    End If
    
    For I = 1 To Len(strURL)
        If Mid(strURL, I, 1) = "%" Then
            If Eval("&H" & Mid(strURL, I + 1, 2)) > 127 Then
                URLDecode = URLDecode & Chr(Eval("&H" & Mid(strURL, I + 1, 2) & Mid(strURL, I + 4, 2)))
                I = I + 5
            Else
                URLDecode = URLDecode & Chr(Eval("&H" & Mid(strURL, I + 1, 2)))
                I = I + 2
            End If
        Else
            URLDecode = URLDecode & Mid(strURL, I, 1)
        End If
    Next
End Function

Function generatOrderNo()
    strDate = CStr(Year(Now()))&FillZero(Cstr(Month(Now())))&FillZero(Cstr(Day(Now())))
    strTime = CStr(Hour(Now()))&CStr(Minute(Now()))&CStr(Second(Now()))
    
    generatOrderNo = strDate & strTime
End Function

Function getTradeDate()
    getTradeDate = CStr(Year(Now()))&FillZero(Cstr(Month(Now())))&FillZero(Cstr(Day(Now())))
End Function

function FillZero(str) 
	ttt=str 
	if len(str)=1 then 
	ttt="0" & str 
	end if 
	FillZero=ttt 
end function

Sub logStr(ByRef logName, ByRef prefix, ByRef str)
    filename = "./" & logName & ".log"
    
    content = now() & ":" & request.ServerVariables("REMOTE_ADDR")
    content = content & "," & prefix &"=[" & str & "]"
    
    Set FSO = Server.CreateObject("Scripting.FileSystemObject")   
    Set TS = FSO.OpenTextFile(Server.MapPath(filename), 8, true) 
    TS.Writeline content
    
    Set TS = Nothing
    Set FSO = Nothing
End Sub

%>