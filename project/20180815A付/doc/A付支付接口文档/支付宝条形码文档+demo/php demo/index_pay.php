<?php  
header("Content-type:text/html;charset=utf-8");


$payKey=$_POST['payKey']; 
$paySecret=$_POST['paySecret']; 
$orderPrice=$_POST['orderPrice']; 
$outTradeNo=$_POST['outTradeNo']; 
$productType=$_POST['productType']; 
$orderTime=$_POST['orderTime']; 
$productName=$_POST['productName']; 
$orderIp=$_POST['orderIp']; 
$returnUrl=$_POST['returnUrl']; 
$notifyUrl=$_POST['notifyUrl']; 
$remark=$_POST['remark']; 
$ip=$_POST['ip']; 
// $payBankAccountNo=$_POST['payBankAccountNo']; 
// $payPhoneNo=$_POST['payPhoneNo']; 
// $payBankAccountName=$_POST['payBankAccountName']; 
// $payCertNo=$_POST['payCertNo']; 
	
	$zd="notifyUrl=".$notifyUrl."&orderIp=".$orderIp."&orderPrice=".$orderPrice."&orderTime=".$orderTime."&outTradeNo=".$outTradeNo."&payKey=".$payKey."&productName=".$productName."&productType=".$productType."&remark=".$remark."&returnUrl=".$returnUrl."&paySecret=".$paySecret;
	
	
	$sign=strtoupper(md5($zd));    //MD5值必须大写
	//echo $zd;
	
	?>


<form method="post" action="https://tgateway.rffbe.top/netGateWayPay/initPay" id="myForm">
<input type="hidden" placeholder="商户支付Key" name="payKey" value="<?=$payKey?>">
<input type="hidden" placeholder="金额" name="orderPrice" value="<?=$orderPrice?>">
<input type="hidden" placeholder="商户支付订单号String" name="outTradeNo" value="<?=$outTradeNo?>">
 <input type="hidden" placeholder="产品类型" name="productType" value="<?=$productType?>">
 <input type="hidden" placeholder="下单时间" name="orderTime" value="<?=$orderTime?>">
<!--  <input type="hidden" placeholder="" name="payBankAccountNo" value="<?=$payBankAccountNo?>">
 <input type="hidden" placeholder="" name="payPhoneNo" value="<?=$payPhoneNo?>">
 <input type="hidden" placeholder="" name="payBankAccountName" value="<?=$payBankAccountName?>">
 <input type="hidden" placeholder="" name="payCertNo" value="<?=$payCertNo?>"> -->
 <input type="hidden" placeholder="支付产品名称String" name="productName" value="<?=$productName?>">
 <input type="hidden" placeholder="下单IP" name="orderIp" value="<?=$orderIp?>">
 <input type="hidden" placeholder="页面通知地址" name="returnUrl" value="<?=$returnUrl?>">
 <input type="hidden" placeholder="后台异步通知" name="notifyUrl" value="<?=$notifyUrl?>">
 <input type="hidden" placeholder="子商户支付Key" name="subPayKey" value="">
 <input type="hidden" placeholder="备注" name="remark" value="<?=$remark?>">
 <input type="hidden" placeholder="签名" name="sign" value="<?=$sign?>">
</form>

<script type="text/javascript">
window.onload= function(){
   document.getElementById('myForm').submit();
}
</script>