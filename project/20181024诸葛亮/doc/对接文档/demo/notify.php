<?php

function sign($money, $record, $sdk) {
    $sign = md5(Number_format($money, 2, '.','') . trim($record) . $sdk);
    return $sign;
}
require 'config.php';
$money = $_REQUEST['money'];
$remark = $_REQUEST['remark'];
$record = $_REQUEST['record'];
$key = $_REQUEST['key'];
$amount = $_REQUEST['amount'];
$order = $_REQUEST['order'];
file_put_contents("lll.txt",$key);
if($key!=KEY)
{
	echo 'key error!';
}
else
{
	//验签，输出成功标识
	echo "ok";
}


?>