<?php
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$key = "h6a2pwcfbdv4qhgsgp90cuj8n35uea7c";   //密钥
$tjurl = "http://yinianpay.com/Pay_Index.html";   //提交地址
$pay_amount = "0.01";
$data = array(
    "pay_memberid" => "10008765",//商户ID
    "pay_orderid" => 'E'.date("YmdHis").rand(100000,999999),//订单号
    "pay_amount" => $pay_amount,  //交易金额
    "pay_applydate" => date("Y-m-d H:i:s"), //订单时间
    "pay_bankcode" => "907",//银行编码
    "pay_notifyurl" =>  "http://www.yourdomain.com/demo/server.php",//服务端返回地址
    "pay_callbackurl" => "http://www.yourdomain.com/demo/page.php",//页面跳转返回地址
);
ksort($data);
$str = "";
foreach ($data as $k => $v) {
    $str = $str . $k . "=" . $v . "&";
}
//echo($md5str . "key=" . $Md5key);
$sign = strtoupper(md5($str . "key=" . $key));
$data["pay_md5sign"] = $sign;
$data['pay_attach'] = "附加信息";
$data['pay_productname'] ='测试';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>支付Demo</title>
</head>
<body>
            <form class="form-inline" method="post" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($data as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" class="btn btn-success btn-lg">测试支付(金额：<?php echo $pay_amount; ?>元)</button>
            </form>
</body>
</html>