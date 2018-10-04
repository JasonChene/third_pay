<?php
require_once 'common.php';
require_once 'config.php';
require_once './lib/paySubmit.class.php';

if (isset($_POST["submit"])) {
    $data = $_POST;

    $pay = new paySubmit($pay_config);
    $order = $pay->orderQuery($data['merOrderNo']);
    if (!$order) {
        die($pay->getErrMsg());
    }

    echo '<pre>请求成功!</pre>';
    echo '<pre>返回报文: <textarea rows="8" cols="100">' . json_encode($order, true) . '</textarea></pre>';

}