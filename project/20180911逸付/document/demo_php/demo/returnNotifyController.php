<?php
/**
 *  支付结果通知
 * Date: 2017/12/29
 * Time: 10:05
 */
@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$input = file_get_contents("php://input");
$input = urldecode($input);
echo  "获取的数据为：".$input;
$returnData = explode("&", $input);

foreach ($returnData as $key=>$value){
    echo $key."=".$value."<br>";
}