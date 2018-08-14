<%@ page language="java" import="java.util.*" contentType="text/html; charset=UTF-8"%>
<%@ page pageEncoding="UTF-8"%>
<%
	String path = request.getContextPath();
%>
<!-- 


把支付参数用表单的方式提交到支付平台




 -->
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<link rel="stylesheet" type="text/css" href="<%=path %>/css/common.css"/>
<script src="<%=path %>/js/JQuery/jquery-1.10.0.min.js"></script>
<title>转发</title>
<style type="text/css">
.bank {
	display: inline-block;
}
.input-out {
	padding-top: 20px;
}
.span-out {
	border-bottom:1px solid #ddd;
	padding: 15px 5px;
}
select,
input[type=number],
input[type=text] { 
	font-size: 16px;
	width: 100%;
	height: 30px;
	border-left: none; 
	border-right: none; 
	border-top: none; 
</style>
</head>
<body>
	<form action="" id="from" method="post">
			<input type="hidden" class="form-control" placeholder="商户号" name="merchantCode" value="<%=request.getAttribute("merchantCode") %>" >
			<input type="hidden" class="form-control" placeholder="商户用户ID" name="outUserId" value="<%=request.getAttribute("outUserId") %>">
			<input type="hidden" class="form-control" placeholder="商户订单号" name="outOrderId" value="<%=request.getAttribute("outOrderId") %>">
			<input type="hidden" class="form-control" placeholder="支付金额" name="totalAmount" value="<%=request.getAttribute("totalAmount") %>">
			<input type="hidden" class="form-control" placeholder="商品名称" name="goodsName" value="<%=request.getAttribute("goodsName") %>">
			<input type="hidden" class="form-control" placeholder="商品描述" name="goodsDescription" value="<%=request.getAttribute("goodsDescription") %>">
			<input type="hidden" class="form-control" placeholder="商户订单时间" name="merchantOrderTime" value="<%=request.getAttribute("merchantOrderTime") %>" />
			<input type="hidden" class="form-control" placeholder="最晚支付时间" name="latestPayTime" value="<%=request.getAttribute("latestPayTime") %>" />
			<input type="hidden" class="form-control" placeholder="后台通知地址" name="notifyUrl" value="<%=request.getAttribute("notifyUrl") %>">
			<input type="hidden" class="form-control" placeholder="商户取货地址" name="merUrl" value="<%=request.getAttribute("merUrl") %>">
			<input type="hidden" class="form-control" placeholder="随机字符串" name="randomStr" value="<%=request.getAttribute("randomStr") %>">
			<input type="hidden" class="form-control" placeholder="扩展字段：" name="ext" value="<%=request.getAttribute("ext") %>">
			<input type="hidden" class="form-control" placeholder="签名" name="sign" value="<%=request.getAttribute("sign") %>">
			
	</form>
	<script type="text/javascript">
$(document).ready(function(){
	$("#from").submit();
});
</script>
</body>
</html>

