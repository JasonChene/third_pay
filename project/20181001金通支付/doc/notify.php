<?php
/*
 * 异步通知页面
 */

$retCode = $_POST['retCode'];
$userId = $_POST['userId'];
$orderNo = $_POST['orderNo'];
$transNo=$_POST['transNo'];
$payAmt = $_POST['payAmt'];
$sign=$_POST['sign'];

//签名密钥使用订单支付类型对应的密钥。
$key = '83v5zj27w0fbn6ryv33caam5e6aqgvrp';

$signStr = '';
$signStr = $signStr . 'orderNo=' . $orderNo;
$signStr = $signStr . '&payAmt=' . $payAmt;
$signStr = $signStr . '&retCode=' . $retCode;
$signStr = $signStr . '&transNo=' . $transNo;
$signStr = $signStr . '&userId=' . $userId;
$signStr = $signStr . '&key=' . $key;

$signStr = md5($signStr);

if ( $result=="1000" && $sign == $signStr) {   
    echo 'ok';
} else {
    echo 'error';
}
?>
