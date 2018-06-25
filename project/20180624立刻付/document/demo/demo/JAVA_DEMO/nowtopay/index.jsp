<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@page trimDirectiveWhitespaces="true"%>
<%

String ordernumber ="111110040440404040" ;//订单号

String subject= "ceshi";  //商品名称

String paymoney= "1";  //交易金额(单位：元)

String attach= "zhanghao";   //交易描述

%>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>收银台</title>
    <style>

 
 
.wrapper{width: 650px;  background: #fff;border-radius: 10px;padding: 40px; margin: 150px auto; line-height: 35px;}
.tips{border: 1px solid #ff7a46; color: #ff7a46;  font-size: 12px; text-align: center; margin: 14px auto; padding: 5px; border-radius: 10px;} 
	.fsbld{font-weight: bold;}
	.fs16{font-size: 16px;}
	.red{color: #ff0000;}
	.mgray{color: #787878;}
	.txc{text-align: center;}
	.mt30{ margin-top: 10px;}
	.mt60{margin-top: 60px;}
	.mb60{margin-bottom: 60px;}
	.inputs{border: 1px solid #c2c2c2; background: none; padding: 5px; text-align: left;width: 266px;border-radius: 5px;}
body, html{height:100%;padding:0;margin:0;}
body{font-size:13px;font-family:"Microsoft YaHei" !important;color:#4d4d4d;background: #fafbfa;}
ul,li,ol,dl,dd,dt,h1,h2,h3,h4,h5,form {padding: 0;margin: 0;list-style: none;list-style-position: outside}
h1,h2,h3,h4,h5{font-size:14px;}
img {border:0;vertical-align:middle;}
em {font-style: normal}
ul,ul li{padding: 0px; margin: 0px;}
input, textarea, button{outline:none !important;resize:none;}
a {color: #4d4d4d;text-decoration: none}	
</style> 
</head>
    

<body>
<div id="header">
<div class="head margin">
<div class="log"></div>
</div>
</div>
<form name='form1' method="post" action="pay.jsp" target="_blank">
<input name="ordernumber" type="hidden"  value="<%=ordernumber%>">
<input name="paymoney" type="hidden" value="<%=paymoney%>">
<input name="attach" type="hidden" value="<%=attach%>">
<!-- 内容 begin  -->
 <div class="wrapper">
 	<div class="wd500">
 		<div class="wd100pc mb60">
			<p class="txc"><img src="style/images/logo.png"></p>
			<div class="tips mt20">温馨提示：您正在使用立刻付支付平台进行充值,请勿关闭该页面</div>
			<div class="mt60 txc"><span class="mgray">订单号：</span><span class="fsbld fs16"><%=ordernumber%>  </span></div> 
			<div class="mt30 txc"><span class="mgray">支付金额：</span><span class="fs55 red"><%=paymoney%>元</span></div>
			<div class="mt60 txc"><label> <input type="radio" checked="checked"  name="banktype" value="MSAli"  /><img src="style/images/alipay.jpg"></label><label><input type="radio"  name="banktype" value="MSWEIXIN"  /><img src="style/images/weixinpay.jpg"></label><label><input type="radio"  name="banktype" value="MSWEIXINWAP"  /><img src="style/images/weixinwappay.jpg"></label></div>
             <div class="mt60 txc"><input type="image" id="btn_pay" src="style/images/pay.png" onClick="return pay();" /></div>
 		</div>
 		 
 		<div class="clearfix"></div>
 	</div>
 </div>
    </form>
</body>
</html>