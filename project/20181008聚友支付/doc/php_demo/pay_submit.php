<?php

//商户号
$mid = "12345";
//商户密钥
$signkey = "xxxxxxxxx";
//商户订单号
$oid = "11111111";
//交易金额
$amt = 100.00;
//方式
$way = 1; //1微信扫码 2支付宝扫码 3微信WAP 4支付宝WAP
//返回地址
$back = "http://www.xxx.com/back.php";
//通知地址
$notify = "http://www.xxx.com/notify.php";
//签名
$sign = md5($mid.$oid.$amt.$way.$back.$notify.$signkey);
		
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<form id="payform" action="https://qrcodepay.app/gateway" method="post">
    <input type="hidden" name="mid" value="<?php echo $mid;?>">
    <input type="hidden" name="oid" value="<?php echo $oid;?>">
    <input type="hidden" name="amt" value="<?php echo $amt;?>">
    <input type="hidden" name="way" value="<?php echo $way;?>">
    <input type="hidden" name="back" value="<?php echo $back;?>">
    <input type="hidden" name="notify" value="<?php echo $notify;?>">
    <input type="hidden" name="sign" value="<?php echo $sign;?>">
</form>
<script type="text/javascript">
    document.getElementById("payform").submit();
</script>
</body>
</html>