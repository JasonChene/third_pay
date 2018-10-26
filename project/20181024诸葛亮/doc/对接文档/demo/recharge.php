<?php
function sign($money, $record, $sdk) {
    $sign = md5(Number_format($money, 2, '.','') . trim($record) . $sdk);
    return $sign;
}
$money = $_REQUEST['money'];//金额
$sdk = $_REQUEST['sdk'];//支付方式以及对应的key
$record = $_REQUEST['record'];//订单号
$refer = $_REQUEST['refer'];//同步通知地址
$notify_url = "http://www.1qcz.com/demo/notify.php"; //异步通知
$url = "http://www.1qcz.com/pay?sdk=" . $sdk . "&record=" . $record . "&money=" . $money . "&refer=".$refer."&notify_url=".$notify_url."&sign=" . sign($money, $record, $sdk);

if($money > 0){
header('Location:'.$url);
}

?>