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
    $order = $pay->scanOrder($data);
    if (!$order) {
        die($pay->getErrMsg());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="cleartype" content="on">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>扫码支付2</title>
    <link type="text/css" href="css/style.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>

<h3 align="center">即将跳转到收银台页面...</h3>

<script type="text/javascript">
    var jumpUrl = "<?php echo $order['jumpUrl']?>";
    $(function() {
        window.setTimeout(function () {
            window.location.href = jumpUrl;
        }, 1000);
    });
</script>
</body>
</html>