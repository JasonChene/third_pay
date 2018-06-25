<?php
header("Content-type:text/html;charset=utf-8");
require('common.php');

// step1: 拼接data
$mid = '100211701160001';
$data = array(
    'head' => array(
        'version' => '1.0',
        'method' => 'sandpay.trade.precreate',
        'productId' => '00000006',
        'accessType' => '1',
        'mid' => $mid,
        'channelType' => '07',
        'reqTime' => date('YmdHis', time())
    ),
    'body' => array(
        'payTool' => '0401',
        'orderCode' => date('YmdHis', time()) + '0601',
        'limitPay' => '1',
        'totalAmount' => '000000000012',
        'subject' => '话费充值',
        'body' => '用户购买话费0.12',
        'txnTimeOut' => '20171230000000',
        'notifyUrl' => 'http://192.168.1.66/sandpay-qr-phpdemo/notifyurl.php',
        'extend' => ''
    )
);

// step2: 私钥签名
$prikey = loadPk12Cert(PRI_KEY_PATH, CERT_PWD);
$sign = sign($data, $prikey);

// step3: 拼接post数据
$post = array(
    'charset' => 'utf-8',
    'signType' => '01',
    'data' => json_encode($data),
    'sign' => $sign
);

// step4: post请求
$result = http_post_json(API_HOST . '/order/create', $post);
$arr = parse_result($result);

//step5: 公钥验签
$pubkey = loadX509Cert(PUB_KEY_PATH);
try {
    verify($arr['data'], $arr['sign'], $pubkey);
} catch (\Exception $e) {
    echo $e->getMessage();
    exit;
}

print_r($arr['data']);