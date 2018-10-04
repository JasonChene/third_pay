<?php

require_once 'common.php';
require_once 'config.php';
require_once './lib/paySubmit.class.php';

if (isset($_POST["submit"])) {
    $data = $_POST;

    $http_pay_url = getPayHttp();

    // 商户后台异步通知url
    $data['notifyUrl'] = $http_pay_url . '/pay_notify.php';

    // 支付成功后，从收银台跳到商户的页面
    $data['callbackUrl'] = $http_pay_url . '/pay_result.php';

    $pay = new paySubmit($pay_config);
    $order = $pay->wapOrder($data);
    if (!$order) {
        die($pay->getErrMsg());
    }


    echo '<pre>请求成功!</pre>';
    echo '<pre>返回报文: <textarea rows="8" cols="100">' . json_encode($order, true) . '</textarea></pre>';

    echo '<a href="' . $order['jumpUrl'] . '" target="_blank">点击提交将跳转到收银台页面</a>';

}