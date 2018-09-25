<?php
$data = [
    "orderAmount"=>"0.01", //金额
    "orderId"=>time().rand(1000,9999),//订单号
    "partner"=>'852100095000003', //商户号
    'payMethod'=>'24',
    "payType"=>"syt",
    "signType"=>"MD5",
    "version"=>"1.0",
];
$key = '9d963ac1f34b482491e9e3747a3a41fb'; //key
ksort($data);
$postString = http_build_query($data);
$signMyself = strtoupper(md5($postString.$key));
$data["sign"] = $signMyself;
$data['productName'] = '9677';
$data['productId'] = '9677';
$data['productDesc'] = '9677';
$data['notifyUrl'] = 'http://qr.sytpay.cn/api/v1/notify.php';
$postString = http_build_query($data);
$url = "http://qr.sytpay.cn/api/v1/create.php?".$postString;
//echo $url;
header("Location: " .$url);

