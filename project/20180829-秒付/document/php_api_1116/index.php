<?php

/* 
 * 支付页面
 * 
 * 
 */
//引入支付类globals
require_once 'globals.php';
/////////demo例子；
        //购买描述
        $body = 'iphone6s手机配件';
        //异步通知
        $notify_url = 'http://www.fashionweddress.top/globalspay.php';
        //网站订单号
        $out_order_no = '201242555';
        //返回地址
        $return_url = 'http://www.fashionweddress.top/globalspay.php';
        //订单名称
        $subject = '购买';
        //金额
        $total_fee = '3';
        $globals = new globals($notify_url,$out_order_no,$return_url,$subject,$total_fee,$body);
        $parame = $globals->parame();
?>
<form action="http://www.gigitong.com/PayOrder/payorder" method="POST">
    <input type="hidden" name="partner" value="<?php echo $parame['partner']; ?>" />
    <input type="hidden" name="user_seller" value="<?php echo $parame['user_seller']; ?>" />
    <input type="hidden" name="out_order_no" value="<?php echo $parame['out_order_no']; ?>" />
    <input type="hidden" name="subject" value="<?php echo $parame['subject']; ?>" />
    <input type="hidden" name="total_fee" value="<?php echo $parame['total_fee']; ?>" />
    <input type="hidden" name="body" value="<?php echo $parame['body']; ?>" />
    <input type="hidden" name="notify_url" value="<?php echo $parame['notify_url']; ?>" />
    <input type="hidden" name="return_url" value="<?php echo $parame['return_url']; ?>" />
    <input type="hidden" name="sign" value="<?php echo $parame['sign']; ?>" />
    <input type="hidden" name="pay_type" value="<?php echo $parame['pay_type']; ?>" />
    <input type="hidden" name="banktype" value="<?php echo $parame['banktype']; ?>" />
    <input type="submit" name="submit" value="環球付支付"/>
</form>

