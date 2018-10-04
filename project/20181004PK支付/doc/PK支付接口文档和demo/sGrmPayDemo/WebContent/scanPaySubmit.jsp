<%@ page language="java" contentType="text/html; charset=UTF-8"%>
<%@ page import="java.util.*"%>
<%@ page import="com.pkzf.pay.dto.*"%>
<%@ page import="com.pkzf.pay.util.*"%>
<%@ page import="com.google.gson.Gson"%>
<%@ page import="com.google.gson.GsonBuilder"%>
<% String contextRoot = request.getContextPath(); %>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>测试Demo</title>
<script src="<%=contextRoot %>/js/jquery.min.js"></script> 
<script src="<%=contextRoot %>/js/qrcode.min.js"></script> 
</head>
<body>
	<%
		request.setCharacterEncoding("UTF-8");
	    CreateWapOrderReqDto reqDto = new CreateWapOrderReqDto();
	    reqDto.setMerId(request.getParameter("merId"));
	    reqDto.setMerOrderNo(request.getParameter("merOrderNo") );
	    reqDto.setVersion(request.getParameter("version"));
	    reqDto.setOrderAmt(request.getParameter("orderAmt"));
	    reqDto.setPayPlat(request.getParameter("payPlat"));
	    reqDto.setOrderTitle(request.getParameter("orderTitle"));
	    reqDto.setOrderDesc(request.getParameter("orderDesc"));
	    reqDto.setNotifyUrl(request.getParameter("notifyUrl"));
	    reqDto.setCallbackUrl(request.getParameter("callbackUrl"));
	    //商户接入秘钥
	    String mercKey = request.getParameter("mercKey");
	    
	    String sign = SignUtil.signData(reqDto.toTreeMap(), mercKey);
	    reqDto.setSign(sign);
	    Gson gson = new GsonBuilder().create();
	     
	    String postStr = gson.toJson(reqDto);
	    
		String url=request.getParameter("reqUrl");
		
		String postResultStr = HttpClientUtil.doPost(url, postStr);
		
		CreateWapOrderRespDto resp = gson.fromJson(postResultStr, CreateWapOrderRespDto.class);
		
		
		String jumpUrl = resp.getJumpUrl()==null?"":resp.getJumpUrl();
	%>
	<table align="center">
	    <tr>
			<td>发送的报文:</td>
		</tr>
		<tr>
		     <td><textarea rows="8" cols="100"><%=postStr %></textarea></td>
		</tr>
		<tr>
			<td>返回报文:</td>
		</tr>
		<tr>
		     <td><textarea rows="8" cols="100"><%=postResultStr %></textarea></td>
		</tr>
		
	</table>
	
	<span align="center">点击提交将跳转到收银台页面</span>
	<a href="<%= jumpUrl%>">提交</a>
	<script>
	
   </script>
</body>
</html>