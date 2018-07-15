<?php
include 'util.php';
/******************* 接口形式请求（非界面跳转），参考此实例 *****************/
$str=  '<?xml version="1.0" encoding="utf-8" standalone="no"?><message application="CertPayOrder" cardNo="6217000010074078454" 
	credentialNo="230224198006132015" credentialType="01" cvv2="" guaranteeAmt="0" merchantFrontEndUrl="https://127.0.0.1:8443/pay-interface/order_request.jsp" merchantId="1000000" 
	merchantOrderAmt="200" merchantOrderDesc="环球地理" merchantOrderId="'.date("YmdHis").'" merchantPayNotifyUrl="https://127.0.0.1:8443/pay-interface/notify.jsp" 
	payerId="" salerId="" userMobileNo="13120033858" userName="张三" validPeriod="" version="1.0.1"/>';	
	
/*****生成请求内容**开始*****/
$strMD5 =  MD5($str,true);	
$strsign =  sign($strMD5);
$base64_src=base64_encode($str);
$msg = $base64_src."|".$strsign;
/*****生成请求内容**结束*****/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $gateway_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
/******异步通知，请参考下面过程******/
$tmp = explode("|", $result);
$resp_xml = base64_decode($tmp[0]);
$resp_sign = $tmp[1];
if(verity(MD5($resp_xml,true),$resp_sign)){//验签
	echo '<br/>响应结果<br/><textarea cols="120" rows="20">'.$resp_xml.'</textarea>';
} else echo '验签失败';
?>