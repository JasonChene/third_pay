<!-- #include file="yeepayCommon.asp" -->
<%
'**************************************************
'* @Description API支付产品通用支付接口范例  
'**************************************************

	'	只有支付成功时API支付才会通知商户.
	''支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url 上：浏览器重定向;服务器点对点通讯.
	
	Dim r0_Cmd
	Dim r1_Code
	Dim r2_TrxId
	Dim r3_Amt
	Dim r4_Cur
	Dim r5_Pid
	Dim r6_Order
	Dim r7_Uid
	Dim r8_MP
	Dim r9_BType
	Dim hmac
	
	Dim bRet
	Dim returnMsg
	
	'解析返回参数
	Call getCallBackValue(r0_Cmd,r1_Code,r2_TrxId,r3_Amt,r4_Cur,r5_Pid,r6_Order,r7_Uid,r8_MP,r9_BType,hmac)
	Call createLog(logName)
	'判断返回签名是否正确（True/False）
	bRet = CheckHmac(r0_Cmd,r1_Code,r2_TrxId,r3_Amt,r4_Cur,r5_Pid,r6_Order,r7_Uid,r8_MP,r9_BType,hmac)
	'以上代码和变量不需要修改.
	
	
	'校验码正确
	returnMsg	= ""
	If bRet = True Then
	  If(r1_Code="1") Then
	  	
		'需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
		'并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.	  	      	  
		
			If(r9_BType="1") Then
				'	在线支付页面返回	
				returnMsg	= returnMsg	&	r3_Amt & "元 支付成功！"				
			ElseIf(r9_BType="2") Then				
	  		'	如果需要应答机制则必须回写以"success"开头的stream,大小写不敏感.
	  		''API支付收到该stream，便认为商户已收到；否则将继续发送通知，直到商户收到为止。
		 		Response.write("success")
				'Call createLog(logName )
				returnMsg	= returnMsg	& "在线支付服务器返回"
			End IF  
	  End IF
	Else
		returnMsg	= returnMsg	&	"交易信息被篡改"
	End If


%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>API支付！</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="css/paytest.css" type="text/css" rel="stylesheet" />
</head>
	<body>
		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="border:solid 1px #107929">
		  <tr>
		    <td><table width="100%" border="0" align="center" cellpadding="5" cellspacing="1">
		  </tr>
		  <tr>
		    <td height="30" align="left"></td>
		  </tr>
		  <tr>
		  	<td height="30" colspan="2" bgcolor="#6BBE18"><span style="color: #FFFFFF">感谢您使用API支付平台</span></td>
		  </tr>
		  <tr>
		  	<td colspan="2" bgcolor="#CEE7BD">API支付通用接口演示：</td>
		  </tr>
		  <tr>
		  	<td align="left" width="30%">&nbsp;&nbsp;商户订单号</td>
		  	<td align="left">&nbsp;&nbsp;<%= r6_Order %></td>
      </tr>
		  <tr>
		  	<td align="left">&nbsp;&nbsp;支付结果</td>
		  	<td align="left">&nbsp;&nbsp;<%= returnMsg %></td>
      </tr>
      <tr>
      	<td height="5" bgcolor="#6BBE18" colspan="2"></td>
      </tr>
      </table></td>
        </tr>
      </table>
	</body>
</html>