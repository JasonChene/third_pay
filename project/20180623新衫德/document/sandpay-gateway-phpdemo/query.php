<?php
require('common.php');

// step1: 拼接data
$data = array(
    'head' => array(
        'version' => '1.0',
        'method' => 'sandpay.trade.query',
        'productId' => '00000007',
        'accessType' => '1',
        'mid' => '100211701160001',
        'channelType' => '07',
        'reqTime' => date('YmdHis', time())
    ),
    'body' => array(
        'orderCode' => '20170900001525675824', //订单号
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
$result = http_post_json(API_HOST . '/order/query', $post);
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