<%@ page language="java" contentType="text/html; charset=UTF-8"%>
<%@ page import="java.util.*"%>
<%@ page import="com.pkzf.pay.dto.*"%>
<%@ page import="com.pkzf.pay.util.*"%>
<%@ page import="com.google.gson.Gson"%>
<%@ page import="com.google.gson.GsonBuilder"%>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>测试Demo</title>

</head>
<body>
	<%
		request.setCharacterEncoding("UTF-8");
	    QueryOrderReqDto reqDto = new QueryOrderReqDto();
	    reqDto.setMerId(request.getParameter("merId"));
	    reqDto.setMerOrderNo(request.getParameter("merOrderNo") );
	    reqDto.setVersion(request.getParameter("version"));
	//    reqDto.setPayNo(request.getParameter("payNo"));
	   
	    //商户接入秘钥
	    String mercKey = request.getParameter("mercKey");
	    
	    String sign = SignUtil.signData(reqDto.toTreeMap(), mercKey);
	    reqDto.setSign(sign);
	    Gson gson = new GsonBuilder().create();
	     
	    String postStr = gson.toJson(reqDto);
	    
		String url=request.getParameter("reqUrl");
		
		String postResultStr = HttpClientUtil.doPost(url, postStr);
		QueryOrderRespDto dto = gson.fromJson(postResultStr, QueryOrderRespDto.class);
		
	%>
	<table align="center">
	    <tr>
			<td>发送的报文:</td>
		</tr>
		<tr>
		     <td><textarea rows="25" cols="120"><%=postStr %></textarea></td>
		</tr>
		<tr>
			<td>返回报文:</td>
		</tr>
		<tr>
		     <td><textarea rows="25" cols="120"><%=postResultStr %></textarea></td>
		</tr>
		
	</table>

</body>
</html>