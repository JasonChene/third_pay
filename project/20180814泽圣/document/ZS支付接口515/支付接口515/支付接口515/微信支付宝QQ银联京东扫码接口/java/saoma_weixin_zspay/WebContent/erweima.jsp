<%@ page language="java" import="java.util.*,com.zspay.SDK.Servlet.SaomaPay"
	contentType="text/html; charset=UTF-8"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core"%>
<%@ taglib prefix="fmt" uri="http://java.sun.com/jsp/jstl/fmt"%>
	<%@ taglib prefix="fn" uri="http://java.sun.com/jsp/jstl/functions" %>
<%@ page pageEncoding="UTF-8"%>
<%
   String path = request.getContextPath();
   SaomaPay saomaPay=new SaomaPay();
  String url=saomaPay.pay();
   
%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>微信支付</title>
<script src="<c:url value="jquery-1.10.0.min.js"/>"></script>
<script src="<c:url value="jquery.qrcode.min.js"/>"></script>
<style>
.code table {
	margin: 0 auto;
}
</style>
</head>


<body>
	<div class="tab_conbox">
		<div class="row" id="wepay">
			<div class="col-md-6" style="text-align:center;">
				
				<br/>
				<img src="<c:url value="/images/logo.png"/>" style="margin-bottom:20px;"/><br/>
				<div  class="code" id="weixincode" style=""></div>
			</div>
		</div>
	</div>
	
	
	<script>
	$(function(){	
		
		 jQuery('#weixincode').qrcode({width: 200,height: 200,correctLevel:0,render: "table",text:'<%=url%>'});
		
	});

	</script>
</body>
</html>