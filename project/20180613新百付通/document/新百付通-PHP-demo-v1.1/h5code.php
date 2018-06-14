<?php
// 实例代码仅供参考；

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['merchantId'] = ID_CODE;
    $data['notifyUrl']  = 'http://xxx.xxx.xxx/notify.php';
    $data['outOrderId'] = date('YmdHis') . mt_rand(10000, 99999);
    $data['subject']    = '棒棒糖';
    $data['body']       = '棒棒糖';
    $data['transAmt']   = $_POST['transAmt'];
    $data['scanType']   = $_POST['scanType'];
    $data['sign']       = getSendSignature($data, ID_CODE, $hzf_private_key);

    writeLog('logs/' . date('Ymd') . '/date_send_h5.txt', json_encode($data));

    $form_data = '<form id="myform" action="' . SEND_H5_URL . '" method="post" accept-charset="utf-8">' . HH;
    foreach ($data as $key => $value) {
        $form_data .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . HH;
    }
    $form_data .= '</form>' . HH;
    $form_data .= '<center><h2>正在跳转</h2></center><script>document.getElementById("myform").submit();</script>';
    echo $form_data;
} else {
    die();
}
