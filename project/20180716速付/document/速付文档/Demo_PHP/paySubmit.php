<?php

header("Content-type: text/html; charset=utf-8");

require("helper.php");

$bankCode = $_POST[AppConstants::$BANK_CODE];
$orderNo = $_POST[AppConstants::$ORDER_NO];
$orderAmount = $_POST[AppConstants::$ORDER_AMOUNT];
$referer = REQ_REFERER;
$customerIp = getClientIp();
$returnParams = $_POST[AppConstants::$RETURN_PARAMS];
$currentDate = date(DATE_TIME_FORMAT,time());

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

$gatewayUrl = GATEWAY_URL;
URLUtils::appendParam($gatewayUrl, AppConstants::$NOTIFY_URL, BACK_NOTIFY_URL, true);
URLUtils::appendParam($gatewayUrl, AppConstants::$RETURN_URL, PAGE_NOTIFY_URL, true);
URLUtils::appendParam($gatewayUrl, AppConstants::$PAY_TYPE, PAY_TYPE);
URLUtils::appendParam($gatewayUrl, AppConstants::$BANK_CODE, $bankCode);
URLUtils::appendParam($gatewayUrl, AppConstants::$MERCHANT_CODE, MER_NO);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_NO, $orderNo);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_AMOUNT, $orderAmount);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_TIME, $currentDate);
URLUtils::appendParam($gatewayUrl, AppConstants::$REQ_REFERER, $referer, true);
URLUtils::appendParam($gatewayUrl, AppConstants::$CUSTOMER_IP, $customerIp);
URLUtils::appendParam($gatewayUrl, AppConstants::$RETURN_PARAMS, $returnParams, true);
URLUtils::appendParam($gatewayUrl, AppConstants::$SIGN, $sign);

//http_redirect($gatewayUrl);

?>

<!DOCTYPE html>
<html>
<head>
    <title>网关支付</title>
</head>
<body>
    <form action="<?=GATEWAY_URL?>" method="post">
        <input type="hidden" name="<?=AppConstants::$NOTIFY_URL?>" value="<?=BACK_NOTIFY_URL?>"/>
        <input type="hidden" name="<?=AppConstants::$RETURN_URL?>" value="<?=PAGE_NOTIFY_URL?>"/>
        <input type="hidden" name="<?=AppConstants::$PAY_TYPE?>" value="<?=PAY_TYPE?>"/>
        <input type="hidden" name="<?=AppConstants::$BANK_CODE?>" value="<?=$bankCode?>"/>
        <input type="hidden" name="<?=AppConstants::$MERCHANT_CODE?>" value="<?=MER_NO?>"/>
        <input type="hidden" name="<?=AppConstants::$ORDER_NO?>" value="<?=$orderNo?>"/>
        <input type="hidden" name="<?=AppConstants::$ORDER_AMOUNT?>" value="<?=$orderAmount?>"/>
        <input type="hidden" name="<?=AppConstants::$ORDER_TIME?>" value="<?=$currentDate?>"/>
        <input type="hidden" name="<?=AppConstants::$REQ_REFERER?>" value="<?=$referer?>"/>
        <input type="hidden" name="<?=AppConstants::$CUSTOMER_IP?>" value="<?=$customerIp?>"/>
        <input type="hidden" name="<?=AppConstants::$RETURN_PARAMS?>" value="<?=$returnParams?>"/>
        <input type="hidden" name="<?=AppConstants::$SIGN?>" value="<?=$sign?>"/>
    </form>
    <script type="text/javascript">
        document.forms[0].submit();
    </script>
</body>
</html>