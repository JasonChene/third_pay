<?php
	
	include './config.php';

	$bb= '1.0';//版本
	$shid= HLConstant::MERCHANT_NO;//商户ID
	$ddh='32423423423';//订单号
	$je='0.1';//金额(必须保留两位小数点，否则验签失败)
	$zftd='wxapi';//支付通道 alapi 支付宝 wxapi 微信
	$ybtz='http://www.test.com';//异步通知地址
	$tbtz='http://www.test.com';//支付成功通知地址
	$ddmc='支付宝';//订单名称
	$ddbz='张三';//付款人名称 能识别是那个会员付的款。 
	
	$key = HLConstant::SIGN_KEY;	

	$sign=md5('shid='.$shid.'&bb='.$bb.'&zftd='.$zftd.'&ddh='.$ddh.'&je='.$je.'&ddmc='.$ddmc.'&ddbz='.$ddbz.'&ybtz='.$ybtz.'&tbtz='.$tbtz.'&'.$key);//MD5加密串

	?>
	<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>提交中</title>
</head>
<body onLoad="document.pay.submit()">
    <form name="pay" action="<?php echo HLConstant::REQUEST_API;?>" method="post">
        <input type="hidden" name="bb" value="<?php echo $bb?>">
        <input type="hidden" name="shid" value="<?php echo $shid?>">
        <input type="hidden" name="ddh" value="<?php echo $ddh?>">
        <input type="hidden" name="je" value="<?php echo $je?>">
        <input type="hidden" name="zftd" value="<?php echo $zftd?>">
        <input type="hidden" name="ybtz" value="<?php echo $ybtz?>">
        <input type="hidden" name="tbtz" value="<?php echo $tbtz?>">
        <input type="hidden" name="ddmc" value="<?php echo $ddmc?>">
        <input type="hidden" name="ddbz" value="<?php echo $ddbz?>">
        <input type="hidden" name="sign" value="<?php echo $sign?>">
		
    </form>
</body>
</html>