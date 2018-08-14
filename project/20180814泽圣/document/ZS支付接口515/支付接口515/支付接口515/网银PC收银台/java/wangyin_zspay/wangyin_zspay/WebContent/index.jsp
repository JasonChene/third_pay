<%@ page language="java" import="java.util.*" contentType="text/html; charset=UTF-8"%>
<%@ page pageEncoding="UTF-8"%>
<%
	String path = request.getContextPath();
%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>收银台支付测试</title>
<link rel="stylesheet" type="text/css" href='<%=path %>/js/bootstrap/css/bootstrap.min.css'/>
<link rel="stylesheet" type="text/css" href="<%=path %>/css/onlinebank/common.css"/>
<link rel="stylesheet" type="text/css" href="<%=path %>/css/onlinebank/bankChoice.css"/>
<script src="<%=path %>/js/JQuery/jquery-1.10.0.min.js"></script>
<script src="<%=path %>/js/bootstrap/js/bootstrap.min.js"></script>
<style>
#confirm-pay .modal-body {
	text-align: center;
	padding: 15px;
}

#confirm-pay .modal-body .btn-primary {
	background: #FE6306;
	border-color: #FE6306;
}
#confirm-pay .modal-body .button button {
	padding: 8px 12px;
	font-size: 18px;
	width: 104px;
}
  #confirm-pay .modal-dialog {
		width: 350px;
		margin: 10% auto;
	}
.tip-content{
margin-bottom: 15px;
margin-top: 15px;
font-size: 18px;
text-align:center;
}
.btn-out button {
	width: 100%;
	background: #f69909;
	border: #f69909;
	color: #fff;
	height: 40px;
	border-radius: 3px;
	font-size: 18px;
	font-family: "Microsoft Yahei", "΢���ź�", SimHei, Tahoma, Arial, Helvetica, STHeiti;
}
.btn-info {
	border-radius: 3px;
	background: #53C0FF;
	border: #53C0FF;
	color: #fff;
	font-family: "Microsoft Yahei", "΢���ź�", SimHei, Tahoma, Arial, Helvetica, STHeiti;
	height:40px;
	width:74px;
	font-size: 18px;
}
.bottom {
	color:#888888;
	margin-bottom: 15px;
	text-align:center;
	margin-top:100px;
}
.bottom a {
	color:#888888;
}
.bottom img {
	vertical-align: middle;
	width: 18px;
}
</style>
</head>

<body>
<div id="back">
	<nav class="navbar navbar-default">
		<div class="container" style="font-size:16px;">
			<strong>收银台支付测试</strong>
		</div>
	</nav>
	
	<div class="container">
		<div class="order_content">
		<form action="<%=path %>/netbankServlet" method="post" id="from">
		<input type="hidden" class="form-control" placeholder="url" name="url" value="<%=path %>/onlinebank/createOrder.do" />
		<div class="input-out">
			<div class="row" style="margin-top:10px;margin-bottom:10px;">
				<span class="col-md-3" style="text-align:right;">商户订单号：</span>
				<div class="col-md-6">
					<input type="text" class="form-control " placeholder="商户订单号" name=outOrderId id="outOrderId" value="ca1a35050666661" readonly="true"/>
				</div>
			</div>
		</div>
		<div class="input-out">
			<div class="row" style="margin-top:10px;margin-bottom:10px;">
				<span class="col-md-3" style="text-align:right;">商品名称：</span>
				<div class="col-md-6">
				   <input type="text" class="form-control" placeholder="商品名称" name="goodsName" id="goodsName" value="测试商品"/>
			   </div>	
			 </div>
		</div>
		<div class="input-out">
			<div class="row" style="margin-top:10px;margin-bottom:10px;">
				<span class="col-md-3" style="text-align:right;">扩展字段：</span>
				<div class="col-md-6">
				   <input type="text" class="form-control" placeholder="扩展字段" name="ext" id="ext" value="用户商户异步回传数据"/>
			   </div>	
			 </div>
		</div>
		<div class="input-out">
			<div class="row" style="margin-top:10px;margin-bottom:10px;">
				<span class="col-md-3" style="text-align:right;">商品描述：</span>
				<div class="col-md-6">
				   <input type="text" class="form-control" placeholder="商品描述" name="goodsDescription" id="goodsDescription" value="用于演示支付"/>
			   </div>	
			 </div>
		</div>
		<div class="input-out">
			<div class="row" style="margin-top:10px;margin-bottom:10px;">
				<span class="col-md-3" style="text-align:right;">支付金额：</span>
				<div class="col-md-6">
				<input type="tel" class="form-control" placeholder="支付金额(元)>=0.1" name="" id="totalAmount1" value="0.1"/>
				<input type="hidden" class="form-control" placeholder="支付金额(元)>=0.1" name="totalAmount" id="totalAmount" value="1"/>
			 </div>	
		 </div>
		</div>
		<div class="btn-out" >
			<div class="row" style="margin-top:20px;margin-bottom:10px;">
			<div class="col-md-3" style="text-align:right;">
			</div>
			<div class="col-md-6" style="text-align:left;">
				<button type="button" class="btn btn-default " style="width:180px;" onclick="check();">支付</button>
			</div>	
			</div>	
		</div>
	</form>
		</div>
	</div>
	
  </div>
  <div class="bottom" id="bottom-out">
		<span></span>
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
	var d = newDate.getDate();
	var h=newDate.getHours();
	var min=newDate.getMinutes();
	var s = newDate.getSeconds();
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
