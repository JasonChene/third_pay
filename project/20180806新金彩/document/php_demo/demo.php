<?php
header("Content-type: text/html; charset=utf-8");
/**
 * 签名 验签 demo
 */
require_once 'src/Provider.php';

try {
    $path = 'http://XXX.XXX.XXX.XXX:8877/v1.0.0/jcpay/jcPayMobile';
    $rsa = new \Ryanc\RSA\Provider('rsa.config.php');
    $signStrMain = array
    (
        'amount' => '1100', //金额，分为单位
        'company_oid' => '',//商户号
        'notify_url' => '',//回调路径
        'order_desc' => '',//描述
        'order_id' => '',//唯一订单号
        'order_name' => '',//商品名称
        'pay_type' => '5'//支付方式,参考帮助文档
    );
    //按照Key排序
    ksort($signStrMain);
    $data = $rsa->privateKeyEncode($signStrMain);
    $signStrMain['sign'] = $data;
    printf(json_encode($signStrMain, JSON_UNESCAPED_SLASHES));
    $body = $rsa->request_post($path, $signStrMain);
    $json = json_decode($body, true);
    $sign = $json['sign'];
    $result = $rsa->verify($json, $sign);
    printf($result);
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>