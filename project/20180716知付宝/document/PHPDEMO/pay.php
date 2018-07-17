<?php
if($_POST){
error_reporting(0);
header("Content-Type: text/html; charset=UTF-8");
include_once('config.php');
$appid = $api_config['appid'];
$appsecret = $api_config['appsecret'];
$out_trade_no=$_POST['out_trade_no'];
$title=$_POST['title'];
$money=$_POST['money'];
$paytype=$_POST['paytype'];
$bankcode=$_POST['bankcode'];
$notify_url=$_POST['notify_url'];
$return_url=$_POST['return_url'];
$sign = strtolower(md5($appid.$out_trade_no.$money.$paytype.$appsecret));
echo '
<div  style="display: none;">
<form name="form1" id="form1" method="post" action="'.$api_url.'"  target="_top">
  <input type="text" name="appid" id="appid" value="'.$appid.'">
  <input type="text" name="out_trade_no" id="out_trade_no" value="'.$out_trade_no.'">
  <input type="text" name="title" id="title" value="'.$title.'">
  <input type="text" name="money" id="money" value="'.$money.'">
  <input type="text" name="bankcode" id="bankcode" value="'.$bankcode.'">
  <input type="text" name="paytype" id="paytype" value="'.$paytype.'">
  <input type="text" name="notify_url" id="notify_url" value="'.$notify_url.'">
  <input type="text" name="return_url" id="return_url" value="'.$return_url.'">
  <input type="text" name="sign" id="sign" value="'.$sign.'">
</form>
</div>
<script>
	document.getElementById("form1").submit();
</script> 	
';exit;
}else{
echo "来源有误";exit;	
	}
?>