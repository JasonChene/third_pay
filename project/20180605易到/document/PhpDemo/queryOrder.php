<?php
/**
 * Created by PhpStorm.
 * User: yangzhe
 * Date: 2017/9/13
 * Time: 12:23
 */
//http://api.easypay168.cn/merExternalCommon/getmerorder.do

/**
 * 订单查询
 */
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
require_once('yidao.php');
$ydpay = new yidao();
//$order = $ydpay->queryOrder("order_1505278259","20170913125059");
$order = $ydpay->queryOrder($_REQUEST['order_id'],$_REQUEST['txntime']);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://api.easypay168.cn/merExternalCommon/getmerorder.do");
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_HEADER,false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// post数据
curl_setopt($ch, CURLOPT_POST,1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $order);
$output = curl_exec($ch);
curl_close($ch);

$response = json_decode($output,true);
if ($response['responseObj']['status']){
    echo 1;
}else{
    echo 0;
}

?>