<?php
require_once 'inc.php';

$pay_way=$_POST['pay_way'];//支付渠道类型
$money=$_POST['money'];//金额，单位分
$order_num=$_POST['order_num'];//商户订单号，唯一
$notify_url=$_POST['notify_url'];//通知url
$return_url=$_POST['return_url'];//返回url
$remark=$_POST['remark'];//备注
$version="1.0";//版本号，当前系统版本号为1.0
$goods_name="小米8青春版";//商品名

//md5签名，注意转大写,参数顺序
$sign=strtoupper(md5($mer_num."&".$pay_way."&".$money."&".$order_num."&".$goods_name."&".$notify_url."&".$return_url."&".$version."&".$mer_accesskey));

?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
    <form name="pay" action="<?php echo $url?>" method="post">
        <input type="hidden" name="version" value="<?php echo $version?>">
        <input type="hidden" name="goods_name" value="<?php echo $goods_name?>">
        <input type="hidden" name="money" value="<?php echo $money?>">
        <input type="hidden" name="mer_num" value="<?php echo $mer_num?>">
        <input type="hidden" name="sign" value="<?php echo $sign?>">
        <input type="hidden" name="return_url" value="<?php echo $return_url?>">
        <input type="hidden" name="notify_url" value="<?php echo $notify_url?>">
        <input type="hidden" name="order_num" value="<?php echo $order_num?>">
        <input type="hidden" name="pay_way" value="<?php echo $pay_way?>">
        <input type="hidden" name="remark" value="<?php echo $remark?>">
    </form>
</body>
</html>
