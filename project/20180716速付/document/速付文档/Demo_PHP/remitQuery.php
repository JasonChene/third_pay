<?php
/**
 * 代付查詢
 *
 *
 */

header("Content-type: text/html; charset=utf-8");

require("helper.php");

$startTime = getMillisecond();
//查詢地址
$queryUrl = "http://api.sufupay.vip/remit_query.html";
//訂單
$orderNo = "2070500920371485";
$currentDate = date(DATE_TIME_FORMAT,time());

$kvs = new KeyValues();
$kvs->add(AppConstants::$MERCHANT_CODE, MER_NO);
$kvs->add(AppConstants::$NOW_DATE, $currentDate);
$kvs->add(AppConstants::$ORDER_NO,$orderNo);

$sign = $kvs->sign();

URLUtils::appendParam($queryUrl, AppConstants::$MERCHANT_CODE, MER_NO,false);
URLUtils::appendParam($queryUrl, AppConstants::$NOW_DATE, urlencode($currentDate));
URLUtils::appendParam($queryUrl, AppConstants::$ORDER_NO, $orderNo);
URLUtils::appendParam($queryUrl, AppConstants::$SIGN, $sign);
//get 请求
$json =  http_request($queryUrl);
echo $json;
echo '<br>';
echo '查询时间(毫秒)：'.(getMillisecond()-$startTime);
?>
