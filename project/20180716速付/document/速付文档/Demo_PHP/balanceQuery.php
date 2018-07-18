<?php
/**
 * 余额查詢
 *
 *
 */

header("Content-type: text/html; charset=utf-8");

require("helper.php");

$startTime = getMillisecond();
//查詢地址
$queryUrl = "http://pay.sufupay.vip/balance.html";

$currentDate = date(DATE_TIME_FORMAT,time());

$kvs = new KeyValues();
$kvs->add(AppConstants::$MERCHANT_CODE, MER_NO);
$kvs->add(AppConstants::$QUERY_TIME,$currentDate);

$sign = $kvs->sign();

URLUtils::appendParam($queryUrl, AppConstants::$MERCHANT_CODE, MER_NO,false);
URLUtils::appendParam($queryUrl, AppConstants::$QUERY_TIME, urlencode($currentDate));
URLUtils::appendParam($queryUrl, AppConstants::$SIGN, $sign);
//get 请求
$json =  http_request($queryUrl);
echo $json;
echo '<br>';
echo '查询时间(毫秒)：'.(getMillisecond()-$startTime);
?>
