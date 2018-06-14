<?php
// 实例代码仅供参考；

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['merchantId'] = ID_CODE;
    $data['queryId']    = time() . mt_rand(10000, 99999);
    $data['outOrderId'] = $_POST['outOrderId'];
    $data['sign']       = getSendSignature($data, ID_CODE, $hzf_private_key);

    $result = curlPost(ORDER_URL, $data);
    if ($result) {
        $resp_data = json_decode($result, true);
        if ($resp_data) {
            if (getRecvSignature($resp_data, ID_CODE, $gwp_public_key)) {
                if ($resp_data['respType'] === 'S' && $resp_data['respCode'] === '00') {
                    unset($resp_data['sign']);
                    foreach ($resp_data as $key => $value) {
                        echo $key . ' ：' . $value . '<br>' . HH;
                    }
                } else {
                    print_r($resp_data);
                    die('签名失败');
                }
            } else {
                print_r($resp_data);
                die('验签失败');
            }
        } else {
            die($result);
        }
    } else {
        die('无数据');
    }
}