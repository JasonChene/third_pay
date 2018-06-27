<?php
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$pay_memberid = "33728";   //商户ID
$pay_orderid = date("YmdHis").rand(100000,999999).$pay_memberid;    //订单号+商户ID
$pay_amount = "1.01";    //交易金额
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_bankcode = "ALIPAY";   //银行编码
$pay_notifyurl = "http://地址/callback.php";   //服务端返回地址
$Md5key = "c2y1ZEg33yku8ViL7vMzLVsrKQUO1V";   //密钥（跳转过程中，请注意检查密钥是否有泄露）
$tjurl = "http://sapi.eboopay.com/Pay_Index.html";   //提交地址
//支付宝支付
$jsapi = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
);

ksort($jsapi);
$md5str = "";
foreach ($jsapi as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
//echo($md5str . "key=" . $Md5key."<br>");
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$jsapi["pay_md5sign"] = $sign;
$jsapi["pay_reserved1"]="客户自定义字段";
//微信支付
$pay_bankcode = "WXZF";   //银行编码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E宝支付Demo</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">

            <form class="form-inline" method="post" target="_blank" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($jsapi as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" class="btn btn-primary btn-lg">支付宝支付(金额：<?php echo $pay_amount; ?>元)</button>
            </form>
        </div>
    </div>
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">
            <form class="form-inline" method="post" target="_blank" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($native as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" class="btn btn-success btn-lg">微信支付(金额：<?php echo $pay_amount; ?>元)</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
</body>
</html>
