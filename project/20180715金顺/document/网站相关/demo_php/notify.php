<?php
include 'util.php';
/******异步通知******/
$result=file_get_contents('php://input', 'r');
$tmp = explode("|", $result);
$resp_xml = base64_decode($tmp[0]);
$resp_sign = $tmp[1];
if(verity(MD5($resp_xml,true),$resp_sign)){//验签
	echo '<br/>响应结果<br/><textarea cols="120" rows="20">'.$resp_xml.'</textarea>';
} else echo '验签失败';
?>