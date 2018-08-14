<?php
// 测试设置
// zlinepay 商户号
// 生产平台 测试用商户号
$merchantCode = "1000000001";
// 签名密钥-与商户号一一对应
// 生产 1000000183对应KEY
$md5Key = "123456ADSEF";
// 生产
$commonUrl = "";
$payUrl = $commonUrl . "ebank/pay.do";
$returnsUrl = $commonUrl . "ebank/return.do";
$queryUrl = $commonUrl . "ebank/queryOrder.do";
// 支付平台分配产品ID
$projectId = "WEPAYPLUGIN_PAY";
?> 