<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<link rel="stylesheet" type="text/css" href="css/common.css"/>
<script src="js/JQuery/jquery-1.10.0.min.js"></script>
<title>支付</title>
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
	<?php
		$outOrderId = "";
		for($i = 0; $i < 32; $i ++) {
			$outOrderId .= rand ( 0, 9 );
		}
		// 蛋蛋的忧伤 转成东八区时间 
		$orderCreateTime = date("YmdHis", time() + 3600 * 8);
	?>
	<div class="title" id="title">
		<img src="images/arrow2.png" />
		<span>商户下单</span>
	</div>
	<div class="content">
		<form action="pay.php" method="post" id="form">
		<div class="input-out">
			<span>商户订单号：</span>
			<input type="text" class="form-control" placeholder="商户订单号" name=outOrderId id="outOrderId" value="<?php echo $outOrderId ?>" readonly="true"/>
		</div>
		<div class="input-out">
			<span>订单时间：</span>
			<input type="text" class="form-control" placeholder="商户订单时间" name="merchantOrderTime" id="merchantOrderTime" value="<?php echo $orderCreateTime ?>" readonly="true"/>
		</div>
		<div class="input-out">
			<span>商户名称：</span>
			<input type="text" class="form-control" placeholder="商品名称" name="goodsName" id="goodsName" value="" />
		</div>
		<div class="input-out">
			<span>商户描述：</span>
			<input type="text" class="form-control" placeholder="商品描述" name="goodsDescription" id="goodsDescription" value="" />
		</div>
		<div class="input-out">
			<span>扩展字段：</span>
			<input type="text" class="form-control" placeholder="扩展字段" name="ext" id="ext" value="用户商户异步回传数据" />
		</div>
		<div class="input-out">
			<span>支付金额：</span>
			<input type="number" class="form-control" placeholder="支付金额(元)>=0.10" name="" id="totalAmount1" value="0.10" />
			<input type="hidden" class="form-control" placeholder="支付金额(元)>=0.10" name="totalAmount" id="totalAmount" value="10" />
		</div>		
		<div class="btn-out">
			<button type="button" class="btn btn-default" onclick="check();">支付</button>		
		</div>
	</form>
	</div>
	<div class="bottom" id="bottom-out">
		<img src="images/phone.png" />
		<span></span>
	</div>
<script type="text/javascript">
if($(window).width()<=340) {
	$('input[type=number],input[type=text]').width("54%");
}
function check(){
	var totalAmount = $("#totalAmount1").val();
	if(totalAmount==""||isNaN(totalAmount)) {
	   alert("请输入大于等于0.1元的支付金额");
	   return false;
	}else {
		var amount = parseFloat(totalAmount).toFixed(2);
		if(amount<0.10) {
			 alert("请输入大于等于0.1元的支付金额");
			 return false;
		}
	}
	$("#totalAmount").val(parseInt(parseFloat(totalAmount)*100));
	$("#form").submit();
}

$(document).ready(function(){
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