<?php 
$api = "http://gateway.beepay.cc/api.php"; //支付网关
$merchant_code='100000001'; //商户号
$merchant_md5='E2660F5DA277B74909914EC82604FABE1'; //商户MD5
$merchant_order_no='AAS_000001'; //订单号
$merchant_goods='Apple'; //商品
$merchant_amount='100.00'; //支付金额
$urlcall='http://www.baidu.com'; //支付成功触发网址，参数以POST方式回传
$urlback='http://www.sina.com'; //支付成功跳转网址
/*
$gateway参数说明
all:		所有通道,
all_wap:	所有WAP通道,
alipay:		支付宝,
alipay_wap:	支付宝WAP,
wechat:		微信,
wechat_wap:	微信,
qq:Q		Q钱包,
qq_wap:		QQ钱包WAP,
bank:		网银
其他类型参考接口文档
*/
$gateway='all';
$merchant_sign=base64_encode(md5('merchant_code='.$merchant_code.'&merchant_order_no='.$merchant_order_no.'&merchant_goods='.$merchant_goods.'&merchant_amount='.$merchant_amount.'&merchant_md5='.$merchant_md5));
?>

<!DOCTYPE html>
<html>
<head>
    <title>网关支付</title>
    <meta charset="utf-8">
</head>
<body>
    <form action="<?php echo $api?>" method="post">
        <input type="hidden" name="merchant_code" value="<?php echo $merchant_code?>"/>
        <input type="hidden" name="merchant_order_no" value="<?php echo $merchant_order_no?>"/>
        <input type="hidden" name="merchant_goods" value="<?php echo $merchant_goods?>"/>
        <input type="hidden" name="merchant_amount" value="<?php echo $merchant_amount?>"/>
        <input type="hidden" name="urlcall" value="<?php echo $urlcall?>"/>
        <input type="hidden" name="urlback" value="<?php echo $urlback?>"/>
        <input type="hidden" name="gateway" value="<?php echo $gateway?>"/>
        <input type="hidden" name="merchant_sign" value="<?php echo $merchant_sign?>"/>
    </form>
    <script type="text/javascript">
        document.forms[0].submit();
    </script>
</body>
</html>