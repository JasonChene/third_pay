<?php
require('common.php');

if ($_POST) {
    // step1: 拼接data
    $data = array(
        'head' => array(
            'version' => '1.0',
            'method' => 'sandpay.trade.pay',
            'productId' => '00000007',
            'accessType' => '1',
            'mid' => $_POST['mid'],
            'channelType' => '07',
            'reqTime' => date('YmdHis', time())
        ),
        'body' => array(
            'orderCode' => $_POST['orderCode'],
            'totalAmount' => $_POST['totalAmount'],
            'subject' => $_POST['subject'],
            'body' => $_POST['body'],
            'txnTimeOut' => $_POST['txnTimeOut'],
            'payMode' => $_POST['payMode'],
            'payExtra' => json_encode(array('payType' => $_POST['payType'], 'bankCode' => $_POST['bankCode'])),
            'clientIp' => $_POST['clientIp'],
            'notifyUrl' => $_POST['notifyUrl'],
            'frontUrl' => $_POST['frontUrl'],
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
    $result = http_post_json(API_HOST . '/order/pay', $post);
    $arr = parse_result($result);

    //step5: 公钥验签
    $pubkey = loadX509Cert(PUB_KEY_PATH);
    try {
        verify($arr['data'], $arr['sign'], $pubkey);
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit;
    }

    // step6： 获取credential
    $data = json_decode($arr['data'], true);
    if ($data['head']['respCode'] == "000000") {
        $credential = $data['body']['credential'];
    } else {
        print_r($arr['data']);
    }

} else {
    echo "不是POST传输  ";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="renderer" content="webkit"/>
    <title>Insert title here</title>
    <script type="text/javascript" src="scripts/paymentjs.js"></script>
    <script type="text/javascript" src="scripts/jquery-1.7.2.min.js"></script>
</head>
<body>
<script>
    function wap_pay() {
        var responseText = $("#credential").text();
        console.log(responseText);
        paymentjs.createPayment(responseText, function (result, err) {
            console.log(result);
            console.log(err.msg);
            console.log(err.extra);
        });
    }
</script>

<div style="display: none">
    <p id="credential"><?php echo $credential; ?></p>
</div>
</body>

<script>
    window.onload = function () {
        wap_pay();
    };
</script>
</html>