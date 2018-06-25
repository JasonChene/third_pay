<?php
require('common.php');

// step1: 拼接data
$data = array(
    'head' => array(
        'version' => '1.0',
        'method' => 'sandPay.fastPay.quickPay.index',
        'productId' => '00000016',
        'accessType' => '1',
        'mid' => $_POST['mid'],
        'channelType' => '07',
        'reqTime' => date('YmdHis', time())
    ),
    'body' => array(
        'userId' => $_POST['userId'],
        'orderCode' => $_POST['orderCode'],
        'orderTime' => $_POST['orderTime'],
        'totalAmount' => $_POST['totalAmount'],
        'subject' => $_POST['subject'],
        'body' => $_POST['body'],
        'currencyCode' => $_POST['currencyCode'],
        'notifyUrl' => $_POST['notifyUrl'],
        'frontUrl' => $_POST['frontUrl'],
        'clearCycle' => $_POST['clearCycle'],
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
    'sign' => urlencode($sign)
);

file_put_contents('temp/log.txt', date('Y-m-d H:i:s', time()) . " 请求报文:" . json_encode($post, 320) . "\r\n",
    FILE_APPEND);
echo json_encode($post);
?>
