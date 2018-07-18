<?php
/**
 * 订单支付
 */

header("Content-type: text/html; charset=utf-8");

require("helper.php");

$orderNo = rand(10000000, 99999999);
$orderAmount = round((rand() / getrandmax()) * 10000) / 100;
$currentDate = date(DATE_TIME_FORMAT,time());
$bankCode = "ABC";
$returnParams = "AB|GG";
$referer = REQ_REFERER;
$customerIp = getClientIp();
$kvs = new KeyValues();
$kvs->add(AppConstants::$NOTIFY_URL, BACK_NOTIFY_URL);
$kvs->add(AppConstants::$RETURN_URL, PAGE_NOTIFY_URL);
$kvs->add(AppConstants::$PAY_TYPE, PAY_TYPE);
$kvs->add(AppConstants::$BANK_CODE, $bankCode);
$kvs->add(AppConstants::$MERCHANT_CODE, MER_NO);
$kvs->add(AppConstants::$ORDER_NO, $orderNo);
$kvs->add(AppConstants::$ORDER_AMOUNT, $orderAmount);
$kvs->add(AppConstants::$ORDER_TIME, $currentDate);
$kvs->add(AppConstants::$REQ_REFERER, $referer);
$kvs->add(AppConstants::$CUSTOMER_IP, $customerIp);
$kvs->add(AppConstants::$RETURN_PARAMS, $returnParams);

$sign = $kvs->sign();

$gatewayUrl = ORDERPAY_URL;

$array = array(
AppConstants::$NOTIFY_URL=>BACK_NOTIFY_URL,
AppConstants::$RETURN_URL=>PAGE_NOTIFY_URL,
AppConstants::$PAY_TYPE=>PAY_TYPE,
AppConstants::$BANK_CODE=>$bankCode,
AppConstants::$MERCHANT_CODE=>MER_NO,
AppConstants::$ORDER_NO=>$orderNo,
AppConstants::$ORDER_AMOUNT=>$orderAmount,
AppConstants::$ORDER_TIME=>$currentDate,
AppConstants::$CUSTOMER_IP=>$customerIp,
AppConstants::$RETURN_PARAMS=>$returnParams,
AppConstants::$REQ_REFERER=>$referer,
AppConstants::$SIGN=>$sign
);
//post请求
$json = http_request($gatewayUrl,$array);
echo $json;
?>
