<?php

header("Content-type: text/html; charset=utf-8");

require("helper.php");



$currentDate = $_POST[AppConstants::$ORDER_TIME];
$merchantCode = $_POST[AppConstants::$MERCHANT_CODE];
$tradeNo = $_POST[AppConstants::$TRADE_NO];
$orderAmount = $_POST[AppConstants::$ORDER_AMOUNT];
$orderTime = $_POST[AppConstants::$ORDER_TIME];
$accountName = $_POST[AppConstants::$ACCOUNT_NAME];
$accountNumber = $_POST[AppConstants::$ACCOUNT_NUMBER];
$bankCode = $_POST[AppConstants::$BANK_CODE];

$kvs = new KeyValues();
$kvs->add(AppConstants::$MERCHANT_CODE, MER_NO);
$kvs->add(AppConstants::$TRADE_NO, $tradeNo);
$kvs->add(AppConstants::$ORDER_AMOUNT, $orderAmount);
$kvs->add(AppConstants::$ORDER_TIME, $currentDate);
$kvs->add(AppConstants::$ACCOUNT_NAME,$accountName);
$kvs->add(AppConstants::$ACCOUNT_NUMBER,$accountNumber);
$kvs->add(AppConstants::$BANK_CODE,$bankCode);

$sign = $kvs->sign();

$gatewayUrl = REMIT_URL;
URLUtils::appendParam($gatewayUrl, AppConstants::$MERCHANT_CODE, MER_NO,false);
URLUtils::appendParam($gatewayUrl, AppConstants::$TRADE_NO, $tradeNo);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_AMOUNT, $orderAmount);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_TIME, urlencode($currentDate));
URLUtils::appendParam($gatewayUrl, AppConstants::$ACCOUNT_NAME, urlencode($accountName));
URLUtils::appendParam($gatewayUrl, AppConstants::$ACCOUNT_NUMBER, $accountNumber);
URLUtils::appendParam($gatewayUrl, AppConstants::$BANK_CODE, $bankCode);
URLUtils::appendParam($gatewayUrl, AppConstants::$SIGN, $sign);
$json =  http_request($gatewayUrl);
echo $gatewayUrl;
echo $json;
exit;
?>

<!DOCTYPE html>
<html>
<head>
<title>网关支付</title>
</head>
<body>
<form action="<?=REMIT_URL?>" method="post">
	<input type="hidden" name="<?=AppConstants::$MERCHANT_CODE?>" value="<?=MER_NO?>" /> 
	<input type="hidden" name="<?=AppConstants::$TRADE_NO?>" value="<?=$tradeNo?>" />
	<input type="hidden" name="<?=AppConstants::$ORDER_AMOUNT?>" value="<?=$orderAmount?>" /> 
	<input type="hidden" name="<?=AppConstants::$ORDER_TIME?>" value="<?=$currentDate?>" /> 
	<input type="hidden" name="<?=AppConstants::$ACCOUNT_NAME?>" value="<?=$accountName?>" /> 
	<input type="hidden" name="<?=AppConstants::$ACCOUNT_NUMBER?>" value="<?=$accountNumber?>" /> 
	<input type="hidden" name="<?=AppConstants::$BANK_CODE?>" value="<?=$bankCode?>" /> 
	<input type="hidden" name="<?=AppConstants::$SIGN?>" value="<?=$sign?>" />
	</form>
<script type="text/javascript">
        document.forms[0].submit();
    </script>
</body>
</html>
