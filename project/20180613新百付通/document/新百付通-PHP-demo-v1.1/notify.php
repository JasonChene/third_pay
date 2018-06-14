<?php
// 实例代码仅供参考；

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST) {
        writeLog('logs/' . date('Ymd') . '/data_notify.txt', json_encode($_POST));
        if (getRecvSignature($_POST, ID_CODE, $gwp_public_key)) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
