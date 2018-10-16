<?php
/* * 
 * 功能：Mustpay页面跳转同步通知页面
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究Mustpay接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见mustpayNotify.class.php中的函数verifyReturn
 */

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>科诺支付页面跳转同步通知页面</title>
</head>
<body>
<?php

    //获取MustPay的通知返回参数，可参考接入指南中同步通知API接口列表(以下仅供参考)

    //商户订单号
    $out_trade_no = $_GET['out_trade_no'];

    //交易金额
    $total_fee = $_GET['total_fee'];

    //商品名称
    $subject = $_GET['subject'];

    //商品详情
    $body = $_GET['body'];

    //商户下单时传递的扩展字段，原样返回
    $extra = $_GET['extra'];


    //注：同步返回接口未做私玥加密 请不要做逻辑处理，只进行查询跳转

    print_r($_GET);exit;
?>
</body>
</html>