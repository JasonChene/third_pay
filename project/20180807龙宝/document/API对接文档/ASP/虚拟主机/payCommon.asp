<!-- #include file="hmac-md5.asp" -->
<!-- #include file="merchantProperties.asp" -->
<%
'**************************************************
'* @Description API֧����Ʒͨ��֧���ӿڷ��� 
'**************************************************

	Dim reqURL_onLine
	Dim reqURL_QryOrd
	Dim reqURL_RefOrd
	Dim p0_Cmd
	Dim p9_SAF
	Dim sbOld

	
Function HTMLcommom(p1_MerId,p2_Order,p3_Amt,p4_Cur,p5_Pid,p6_Pcat,p7_Pdesc,p8_Url,pa_MP,pd_FrpId,pr_NeedResponse)

	'	����֧�������ַ 
	reqURL_onLine = "http://localhost/GateWay/ReceiveBank.aspx"    '	
	'����ʱ����ʹ�÷������ṩ�Ĳ����̻���ź���Կ�������̻���ż���Կ���������򡣣�
	 
	'2.��Ӧ�ûὫ���������һ�����������������⣬��ᱨ����ϸ��Ϣ��
	'3.�����������ȷ���󣬸�Ӧ�ý�����һ��ģ���֧���ɹ����ݣ�������ʹ�ø����ݲ���֧������Ľ��ճ���

	' ҵ������
	''����֧�����󣬹̶�ֵ ��Buy�� .	
	p0_Cmd = "Buy"
	
	'	�ͻ���ַ
	''Ϊ��1��: ��Ҫ�û����ͻ���ַ����API֧��ϵͳ;Ϊ��0��: ����Ҫ��Ĭ��Ϊ ��0��.
	p9_SAF = "0"				'��Ҫ��д�ͻ���Ϣ 0������Ҫ  1:��Ҫ
	
	sbOld  = ""

	'	����ǩ������һ�������ĵ��б�����ǩ��˳�����
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
	
		' ȡ��ƴ��hmac���ַ�������
	strlen = len(sbOld)
	' �ж��ַ������ȣ��������Ϊ56��56+64*n������Զ���0���������˲���������asp���Ե�md5���ܴ���bug
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
	
	' ����ƴ���ַ���
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

	'ȡ��ǩ������ǰ���ַ�����һ�������ĵ��б�����ǩ��˳�����
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


'ȡ������֧�����������еĲ���
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
 

 

'callback����֧�����������أ���������Ե�ͨѶ
'д�� onLine.log �����������Խӿ�
Sub createLog(ByRef returnMsg)
    filename = "./" & returnMsg & ".log"
    content = now()		&	","							&	request.ServerVariables("REMOTE_ADDR")
    content = content &	","							&	returnMsg
    content = content &	",�̻�������:["	& r6_Order & "]"
    content = content &	",֧�����:["		& r3_Amt & "]"
    content = content &	",ǩ������:["		& hmac & "]"
    
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
    content = content &	",�̻�������:["	& Order & "]"
    content = content &	",str:[" & str & "]"
    
    Set FSO = Server.CreateObject("Scripting.FileSystemObject")   
    Set TS = FSO.OpenTextFile(Server.MapPath(filename),8,true) 
    TS.write content   
    TS.Writeline ""
    TS.Writeline ""
    Set TS = Nothing   
    Set FSO = Nothing   
End Sub

' �ж��ύ�����Ƿ��������
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