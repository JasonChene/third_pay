<?php
/**
 * 回调通知
 * Date: 2017/12/29
 * Time: 10:05
 */


$input = file_get_contents("php://input");
$input=urldecode($input);
echo  "回调的数据为：".$input;
$returnData = explode("&", $input);

foreach ($returnData as $key=>$value){
    echo $key."=".$value."<br>";
}


