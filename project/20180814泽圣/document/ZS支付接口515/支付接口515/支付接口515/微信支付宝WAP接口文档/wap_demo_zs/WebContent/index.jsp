<%@page import="com.zspay.SDK.util.StringUtil"%>
<%@ page language="java" import="java.util.*" contentType="text/html; charset=UTF-8"%>
<%@ page pageEncoding="UTF-8"%>
<%
	String path = request.getContextPath();
    String outOrderId=StringUtil.getRandomNum(20);
%>
<!-- 
模拟用户下单
 -->
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<link rel="stylesheet" type="text/css" href="<%=path %>/css/common.css"/>
<script src="<%=path %>/js/JQuery/jquery-1.10.0.min.js"></script>
<title>商户下单</title>
<style type="text/css">
.input-out {
	padding-top: 20px;
	height: 36px;
}
.input-out span {
	font-size: 16px;
	vertical-align: sub;
}
input[type=number],
input[type=text] { 
	width: 62%;
	float:right;
}
</style>
</head>
<body>
	<div class="title" id="title">
		<img src="<%=path %>/images/arrow2.png" />
		<span>商户下单</span>
	</div>
	<div class="content">
		<form action="<%=path %>/pay.do" method="post" id="from">
		<div class="input-out">
		<span>商户号：</span>
		<input type="text"  class="form-control" placeholder="商户号" name="merchantCode" value="1000000001"/>
		</div>
		<div class="input-out">
			<span>商户订单号：</span>
			<input type="text" class="form-control" placeholder="商户订单号" name=outOrderId id="outOrderId" value="<%=outOrderId %>" readonly="true"/>
		</div>
		<div class="input-out">
			<span>订单时间：</span>
			<input type="text" class="form-control" placeholder="商户订单时间" name="merchantOrderTime" id="merchantOrderTime" value="2015-11-05 10:20:30"/>
		</div>
		<div class="input-out">
			<span>商户名称：</span>
			<input type="text" class="form-control" placeholder="商品名称" name="goodsName" id="goodsName" value="测试商品"/>
		</div>
		<div class="input-out">
			<span>商户描述：</span>
			<input type="text" class="form-control" placeholder="商品描述" name="goodsDescription" id="goodsDescription" value="用于演示支付">
		</div>
		<div class="input-out">
			<span>支付金额：</span>
			<input type="number" class="form-control" placeholder="支付金额(元)>2.00" name="" id="totalAmount1" value="2.01">
			<input type="hidden" class="form-control" placeholder="支付金额(元)>2.00" name="totalAmount" id="totalAmount" value="201">
		</div>		
		<div class="btn-out">
			<button type="button" class="btn btn-default" onclick="check();">支付</button>		
		</div>
	</form>
	</div>
	<div class="bottom" id="bottom-out">
		<img src="<%=path %>/images/phone.png" />
		<span>客服电话：</span>
	</div>
<script type="text/javascript">
if($(window).width()<=340) {
	$('input[type=number],input[type=text]').width("54%");
}
function check()
{
	var totalAmount = $("#totalAmount1").val();
	if(totalAmount==""||isNaN(totalAmount))
		{
		   alert("请输入大于等于0.1元的支付金额");
		   return false;
		}else 
		{
			var amount = parseFloat(totalAmount).toFixed(2);
			if(amount<0.10)
			{
				 alert("请输入大于等于0.1元的支付金额");
				  return false;
			}
		}
	$("#totalAmount").val(parseInt(parseFloat(totalAmount)*100));
	$("#from").submit();
}
var chars = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];

function generateMixed(n) {
     var res = "";
     for(var i = 0; i < n ; i ++) {
         var id = Math.ceil(Math.random()*60);
         res += chars[id];
     }
     return res;
}
$(document).ready(function(){
	$("#outOrderId").val(generateMixed(24));
	var newDate = new Date();
	var y = newDate.getFullYear();    
	var m = newDate.getMonth()+1;//获取当前月份的日期    
	if(parseInt(m)<10)
		{
		m="0"+m;
		}
	var d = newDate.getDate();
	if(parseInt(d)<10)
	{
	d="0"+d;
	}
	var h=newDate.getHours();
	if(parseInt(h)<10)
	{
	h="0"+h;
	}
	var min=newDate.getMinutes();
	if(parseInt(min)<10)
	{
		min="0"+min;
	}
	var s = newDate.getSeconds();
	if(parseInt(s)<10)
	{
		s="0"+s;
	}
	$("#merchantOrderTime").val(y+"-"+m+"-"+d+" "+h+":"+min+":"+s);
	is_weixn();
});
function is_weixn(){  
	   var ua = navigator.userAgent.toLowerCase();  
	    if(ua.match(/MicroMessenger/i)=="micromessenger") {  
	       $("#title").hide();
	    }  
 } 
</script>
</body>
</html>