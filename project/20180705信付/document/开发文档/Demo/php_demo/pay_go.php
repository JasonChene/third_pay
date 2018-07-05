<?php

include_once("config/pay_config.php");

/*
 * 获取表单数据
 * */
$order_id = (string) date("YmdHis"); //您的订单Id号，你必须自己保证订单号的唯一性，本平台不会限制该值的唯一性
$payType = $_POST['payType'];  //充值方式：bank为网银，card为卡类支付
$account = $_POST['account'];  //充值的账号
$amount = $_POST['amount'];   //充值的金额
//网银支付
if ('bank' == $payType) {
    $bankType = $_POST['bankType'];   //银行类型


    /*
     * 提交数据
     * */
    include_once("lib/class.bankpay.php");
    $bankpay = new bankpay();
    $bankpay->parter = $merchant_id;  //商家Id
    $bankpay->key = $merchant_key; //商家密钥
    $bankpay->type = $bankType;   //商家密钥
    $bankpay->value = $amount;    //提交金额
    $bankpay->orderid = $order_id;   //订单Id号
    $bankpay->callbackurl = $bank_callback_url; //下行url地址
    $bankpay->hrefbackurl = $bank_hrefbackurl; //下行url地址
    //发送
    $bankpay->send();
}
//卡类支付
else if ('card' == $payType) {
    $cardType = $_POST['cardType'];   //卡类型
    $card_number = $_POST['card_number'];  //卡号
    $card_password = $_POST['card_password'];  //卡密
    /*
     * 提交数据
     * */
    include_once("lib/class.cardpay.php");
    $cardpay = new cardpay();
    $cardpay->type = $cardType;   //卡类型	
    $cardpay->cardno = $card_number;   //卡号
    $cardpay->cardpwd = $card_password;  //卡密
    $cardpay->value = $amount;    //提交金额
    $cardpay->restrict = $restrict;  //地区限制, 0表示全国范围
    $cardpay->orderid = $order_id;   //订单号
    $cardpay->callbackurl = $callback_url; //下行url地址
    $cardpay->parter = $merchant_id;  //商家Id
    $cardpay->key = $merchant_key; //商家密钥
    //发送
    $result = $cardpay->send();

    /*
     * 处理结果
     * */
    switch ($result) {
        case '0':
            header("location: pay_card_finish.php?order_id=$order_id");
            break;
        case '-1':
            header("location: pay_card_finish.php?order_id=$order_id");
            break;
        case '-2':
            print('签名错误');
            break;
        case '-3':
            print('<script language="javascript">alert("对不起，您填写的卡号卡密有误！"); history.go(-1);</script>');
            break;
        case '-999':
            print('<script language="javascript">alert("对不起，接口维护中，请选择其他的充值方式！"); history.go(-1);</script>');
            break;
        default:
            print('未知的返回值, 请联系平台官方！');
            break;
    }
}

?>