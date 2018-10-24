<?php

/**
 * ---------------------通知异步回调接收页-------------------------------
 * 
 * 此页就是您之前传给bearpay的notify_url页的网址
 * 支付成功，BearPay会根据您之前传入的网址，回调此页URL，post回参数
 * 
 * --------------------------------------------------------------
 */


$orderid = $_POST["out_trade_no"];
$money = $_POST["money"];
$token = $_POST["token"];
$returncode = $_POST["return_code"];

    //校验传入的参数是否格式正确，略
$shopId = "bearpay的商户ID";
$key = "bearpay的key";

$temps = md5($shopId . '$' . $orderid . '$' . $money . '$' . $key . '$' . $returncode);

if ($temps != $token) {
    echo "failure";
} else {
        //校验key成功，是自己人。执行自己的业务逻辑：加余额，订单付款成功，装备购买成功等等。
    echo "success";
}

?>