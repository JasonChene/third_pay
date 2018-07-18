<?php
/**
 * 订单查询
 *
 *
 */

header("Content-type: text/html; charset=utf-8");

require("helper.php");
//查詢地址
$remitQueryUrl = "https://api.huitongvip.com/query.html";

$orderNo = "2065820912453681";

$currentDate = date(DATE_TIME_FORMAT,time());
$kvs = new KeyValues();
$kvs->add(AppConstants::$MERCHANT_CODE, MER_NO);
$kvs->add(AppConstants::$NOW_DATE, $currentDate);
$kvs->add(AppConstants::$ORDER_NO,$orderNo);

$sign = $kvs->sign();

$array = array(
AppConstants::$ORDER_NO=>$orderNo,
AppConstants::$MERCHANT_CODE=>MER_NO,
AppConstants::$NOW_DATE=>$currentDate,
AppConstants::$SIGN=>$sign);
//post请求
$json =  http_request($remitQueryUrl,$array);

echo $json;
?>
