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
</head>
<body>
	<%
		request.setCharacterEncoding("UTF-8");
	    CreateWapOrderReqDto reqDto = new CreateWapOrderReqDto();
	    reqDto.setMerId(request.getParameter("merId"));
	    
	    reqDto.setMerOrderNo(System.currentTimeMillis()+"");
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
	    
		//String url="http://127.0.0.1:8080/grmApp/createWapOrder.do";
		String url=request.getParameter("reqUrl");
		
		String postResultStr = HttpClientUtil.doPost(url, postStr);
		
		CreateWapOrderRespDto resp = gson.fromJson(postResultStr, CreateWapOrderRespDto.class);
		String respCode = resp.getRespCode();
		String respMsg = resp.getRespMsg();
		
		String jumpUrl = resp.getJumpUrl()==null?"":resp.getJumpUrl();
	%>
	<span align="center">即将跳转到支付宝付款</span>
	<script>
	var jumpUrl = "<%= jumpUrl%>";
	var respCode = "<%= respCode%>";
	var respMsg = "<%= respMsg%>";
	$(document).ready(function () {
       if(respCode == "0000"){
    	      window.setTimeout(function () {
               window.location.href=jumpUrl;
           }, 1000);
       }else{
    	      alert(respMsg);
       }
        
        
    });
   </script>

</body>
</html>