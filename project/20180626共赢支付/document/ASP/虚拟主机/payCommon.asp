<!-- #include file="hmac-md5.asp" -->
<!-- #include file="merchantProperties.asp" -->
<%
'**************************************************
'* @Description API支付产品通用支付接口范例 
'**************************************************

	Dim reqURL_onLine
	Dim reqURL_QryOrd
	Dim reqURL_RefOrd
	Dim p0_Cmd
	Dim p9_SAF
	Dim sbOld

	
Function HTMLcommom(p1_MerId,p2_Order,p3_Amt,p4_Cur,p5_Pid,p6_Pcat,p7_Pdesc,p8_Url,pa_MP,pd_FrpId,pr_NeedResponse)

	'	在线支付请求地址 
	reqURL_onLine = "http://localhost/GateWay/ReceiveBank.aspx"    '	
	'测试时必须使用范例中提供的测试商户编号和密钥（测试商户编号及密钥见范例程序。）
	 
	'2.该应用会将请求参数逐一分析，如请求有问题，则会报告详细信息；
	'3.请求参数如正确无误，该应用将生成一个模拟的支付成功数据，您可以使用该数据测试支付结果的接收程序。

	' 业务类型
	''在线支付请求，固定值 ”Buy” .	
	p0_Cmd = "Buy"
	
	'	送货地址
	''为“1”: 需要用户将送货地址留在API支付系统;为“0”: 不需要，默认为 ”0”.
	p9_SAF = "0"				'需要填写送货信息 0：不需要  1:需要
	
	sbOld  = ""

	'	进行签名处理，一定按照文档中标明的签名顺序进行
	sbOld = sbOld + p0_Cmd
	sbOld = sbOld + p1_MerId
	sbOld = sbOld + p2_Order
	sbOld = sbOld + CStr(p3_Amt)		
	sbOld = sbOld + p4_Cur					
	sbOld = sbOld + p5_Pid
	sbOld = sbOld + p6_Pcat
	sbOld = sbOld + p7_Pdesc
	sbOld = sbOld + p8_Url
	sbOld = sbOld + p9_SAF
	sbOld = sbOld + pa_MP
	sbOld = sbOld + pd_FrpId
	sbOld = sbOld + pr_NeedResponse
	
		' 取得拼凑hmac的字符串长度
	strlen = len(sbOld)
	' 判断字符串长度，如果长度为56或56+64*n则进行自动补0操作。做此操作是由于asp语言的md5加密存在bug
	if strlen >56 then
	  if (strlen - 56) mod 64 = 0 then
	       if instr(p3_Amt,".") = 0 then
	        p3_Amt = CStr(p3_Amt) & ".0"    
		   else
	        p3_Amt = CStr(p3_Amt) & "0" 		
		   end if
	  end if
	else
	  if strlen = 56 then
	       if instr(p3_Amt,".") = 0 then
	        p3_Amt = CStr(p3_Amt) & ".0"    
		   else
	        p3_Amt = CStr(p3_Amt) & "0" 		
		   end if
	  end if
	end if
	
	' 重新拼凑字符串
	sbOld  = ""
	sbOld = sbOld + p0_Cmd
	sbOld = sbOld + p1_MerId
	sbOld = sbOld + p2_Order
	sbOld = sbOld + CStr(p3_Amt)		
	sbOld = sbOld + p4_Cur					
	sbOld = sbOld + p5_Pid
	sbOld = sbOld + p6_Pcat
	sbOld = sbOld + p7_Pdesc
	sbOld = sbOld + p8_Url
	sbOld = sbOld + p9_SAF
	sbOld = sbOld + pa_MP
	sbOld = sbOld + pd_FrpId
	sbOld = sbOld + pr_NeedResponse
	
	Call logStr(logName,p2_Order, sbOld)
	
	HTMLcommom = HmacMd5(sbOld,merchantKey) 
	
End Function


Function getCallbackHmacString(r0_Cmd,r1_Code,r2_TrxId,r3_Amt,r4_Cur,r5_Pid,r6_Order,r7_Uid,r8_MP,r9_BType)

	'取得签名数据前的字符串，一定按照文档中标明的签名顺序进行
	sbOld = sbOld + CStr(p1_MerId)
	sbOld = sbOld + r0_Cmd
	sbOld = sbOld + r1_Code
	sbOld = sbOld + r2_TrxId
	sbOld = sbOld + r3_Amt
	sbOld = sbOld + r4_Cur
	sbOld = sbOld + r5_Pid
	sbOld = sbOld + r6_Order
	sbOld = sbOld + r7_Uid
	sbOld = sbOld + r8_MP
	sbOld = sbOld + r9_BType

	Call logStr(logName,r6_Order, sbOld)
	getCallbackHmacString = HmacMd5(sbOld,merchantKey)
	
End Function


Function CheckHmac(r0_Cmd,r1_Code,r2_TrxId,r3_Amt,r4_Cur,r5_Pid,r6_Order,r7_Uid,r8_MP,r9_BType,hmac)
	if(hmac=getCallbackHmacString(r0_Cmd,r1_Code,r2_TrxId,r3_Amt,r4_Cur,r5_Pid,r6_Order,r7_Uid,r8_MP,r9_BType)) Then
		CheckHmac = True
	ELSE
		CheckHmac = False
	END IF
End Function


'取得在线支付返回数据中的参数
Sub getCallBackValue(ByRef r0_Cmd,ByRef r1_Code,ByRef r2_TrxId,ByRef r3_Amt,ByRef r4_Cur,ByRef r5_Pid,ByRef r6_Order,ByRef r7_Uid,ByRef r8_MP,ByRef r9_BType,ByRef hmac)
	r0_Cmd		=	Request.QueryString("r0_Cmd")
	r1_Code		=	Request.QueryString("r1_Code")
	r2_TrxId	=	Request.QueryString("r2_TrxId")
	r3_Amt		=	Request.QueryString("r3_Amt")
	r4_Cur		=	Request.QueryString("r4_Cur")
	r5_Pid		=	Request.QueryString("r5_Pid")
	r6_Order	=	Request.QueryString("r6_Order")
	r7_Uid		=	Request.QueryString("r7_Uid")
	r8_MP			=	Request.QueryString("r8_MP")
	r9_BType	=	Request.QueryString("r9_BType") 	
	hmac			=	Request.QueryString("hmac")
End Sub
 

 

'callback在线支付服务器返回，服务器点对点通讯
'写入 onLine.log 这里用来调试接口
Sub createLog(ByRef returnMsg)
    filename = "./" & returnMsg & ".log"
    content = now()		&	","							&	request.ServerVariables("REMOTE_ADDR")
    content = content &	","							&	returnMsg
    content = content &	",商户订单号:["	& r6_Order & "]"
    content = content &	",支付金额:["		& r3_Amt & "]"
    content = content &	",签名数据:["		& hmac & "]"
    
    Set FSO = Server.CreateObject("Scripting.FileSystemObject")   
    Set TS = FSO.OpenTextFile(Server.MapPath(filename),8,true) 
    TS.write content   
    TS.Writeline ""
    TS.Writeline ""
    Set TS = Nothing   
    Set FSO = Nothing   
End Sub

Sub logStr(ByRef returnMsg,Order, str)
    filename = "./" & returnMsg & ".log"
    content = now()		&	","							&	request.ServerVariables("REMOTE_ADDR")
    content = content &	",商户订单号:["	& Order & "]"
    content = content &	",str:[" & str & "]"
    
    Set FSO = Server.CreateObject("Scripting.FileSystemObject")   
    Set TS = FSO.OpenTextFile(Server.MapPath(filename),8,true) 
    TS.write content   
    TS.Writeline ""
    TS.Writeline ""
    Set TS = Nothing   
    Set FSO = Nothing   
End Sub

' 判断提交参数是否包含中文
Function HasChinese(str) 
HasChinese=false 
dim i 
for i=1 to Len(str) 
if Asc(Mid(str,i,1))<0 then 
HasChinese=true 
exit for 
end if 
next 
End Function

Function URLDecode(enStr)
	dim deStr,strSpecial
	dim c,i,v
	deStr=""
	strSpecial="!""#$%&'()*+,.-_/:;<=>?@[\]^`{|}~%"
	for i=1 to len(enStr)
    c=Mid(enStr,i,1)
    if c="%" then
      v=eval("&h"+Mid(enStr,i+1,2))
      if inStr(strSpecial,chr(v))>0 then
        deStr=deStr&chr(v)
        i=i+2
      else
        v=eval("&h"+ Mid(enStr,i+1,2) + Mid(enStr,i+4,2))
        deStr=deStr & chr(v)
        i=i+5
      end if
    else
      if c="+" then
        deStr=deStr&" "
      else
        deStr=deStr&c
      end if
    end if
  next
  URLDecode=deStr
End Function
%>