<?php
// 后台回调通知url

require_once 'common.php';
require_once 'config.php';
require_once './lib/payNotify.class.php';

$pay = new payNotify($pay_config);
$result = $pay->notify();

if ($result) {
    // 响应报文, 处理成功请返回success
    if ($result['return_code']) {
        die($result['return_code']);
    }
}
