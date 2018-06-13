<?php
include 'config.php';

//写接收的参数日志
$logs = fopen("pay.log", "a+");
fwrite($logs, "\r\n" . date("Y-m-d H:i:s") . "  回调信息：" . json_encode($_REQUEST) . " \r\n");
fclose($logs);

$ordno = $_REQUEST["ordno"];
$orderid = $_REQUEST["orderid"];
$price = $_REQUEST["price"];
$realprice = $_REQUEST["realprice"];
$orderuid = $_REQUEST["orderuid"];
$key = $_REQUEST["key"];


$check = md5($orderid . $orderuid . $ordno . $price . $realprice . $token);

if($key == $check){
    //如果key验证成功，并且金额验证成功，只返回success【小写】字符串；
    //业务处理代码..........

    //在目录下创建一个文件；
    $logs = fopen($orderid . ".lock", "a+");
    fwrite($logs, $orderid);
    fclose($logs);

    exit("success");//只输出success，前面不要输出任何东西，包括空格转行回车等；
}else{
    exit("fail");
}



?>
