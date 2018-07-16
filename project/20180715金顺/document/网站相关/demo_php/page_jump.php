<?php
include 'util.php';
/******************* 页面跳转下单，直连网银（快捷H5下单，微信WAP参考此文档） *****************/
$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?>
	<message accountType="0" application="SubmitOrder" bankId="" bizType="" credentialNo="" credentialType="" guaranteeAmt="0" 
	merchantFrontEndUrl="https://127.0.0.1:8443/pay-interface/order_request.jsp"
	merchantId="1000000" merchantOrderAmt="1" merchantOrderDesc="环球地理" merchantOrderId="'.date("YmdHis").'" 
	merchantPayNotifyUrl="https://127.0.0.1:8443/pay-interface/notify.jsp" msgExt="" orderTime="20161027192237" payMode="0" 
	payerId="" rptType="1" salerId="" userMobileNo="13333333333" userName="" userType="1" version="1.0.1"/>';	
/*****生成请求内容**开始*****/
$strMD5 =  MD5($str,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str);
$msg = $base64_src."|".$strsign;
/*****生成请求内容**结束*****/
$def_url =  '<div style="text-align:center">';
$def_url .= '<body onLoad="//document.ipspay.submit();">网银订单确认';
$def_url .= '<form name="ipspay" action="'.$gateway_url.'" method="post">';
$def_url .=	'<input name="msg" type="hidden" value="'.$msg.'" /><input type="submit" value="提交"/>';
$def_url .=	'</form></div>';
echo $def_url;
?>