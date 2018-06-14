<?php
// 实例代码仅供参考；

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['merchantId'] = ID_CODE;
    $data['notifyUrl']  = 'http://xxx.xxx.xxx/notify.php'; // 外网能访问
    $data['outOrderId'] = 'JY' . date('YmdHis');
    $data['subject']    = 'subject';
    $data['transAmt']   = $_POST['transAmt'];
    $data['scanType']   = $_POST['scanType'];
    $data['sign']       = getSendSignature($data, ID_CODE, $hzf_private_key);

    writeLog('logs/' . date('Ymd') . '/date_send.txt', json_encode($data));

    $result = curlPost(SEND_URL, $data);
    if ($result) {

        writeLog('logs/' . date('md') . '/date_resp.txt', $result);

        $resp_data = json_decode($result, true);
        if ($resp_data) {
            if (getRecvSignature($resp_data, ID_CODE, $gwp_public_key)) {
                if ($resp_data['respType'] === 'R' && $resp_data['respCode'] === '99') {
                    $show_data['code_url'] = $resp_data['payCode'];
                    $show_data['amount']   = $resp_data['transAmt'];
                    $show_data['order_no'] = $resp_data['outOrderId'];
                    $show_data['trade_no'] = $resp_data['localOrderId'];
                } else {
                    print_r($resp_data);
                    die('交易失败');
                }
            } else {
                print_r($resp_data);
                die('验签失败');
            }
        } else {
            die($result);
        }
    } else {
        die('无返回数据');
    }
} else {
    die();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>二维码支付</title>
    <script type="text/javascript" src="jquery-qrcode/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-qrcode/jquery.qrcode.min.js"></script>
    <script type="text/javascript">
    jQuery(function() {
        jQuery('#output').qrcode("<?php echo $show_data['code_url']; ?>");
    })
    </script>
</head>

<body>
    <div id="output" style="text-align: center;"></div>
    <div class="info" style="text-align: center;">
        <p><a href="<?php echo $show_data['code_url']; ?>"><?php echo $show_data['code_url']; ?></a></p>
        <p>订单金额：<?php echo $show_data['amount']; ?></p>
        <p>商户订单号：<?php echo $show_data['order_no']; ?></p>
        <p>系统订单号：<?php echo $show_data['trade_no']; ?></p>
    </div>
</body>

</html>