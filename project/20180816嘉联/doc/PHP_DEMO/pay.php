<?php
date_default_timezone_set("Asia/Shanghai");
header("Content-type: text/html; charset=utf-8");
if($_POST) {
    $pay_amount = $_POST['amount'];
    $pay_memberid = "xxxxx";   //商户号，商户后台【API管理-API开发文档】中获取
    $pay_orderid = date("YmdHis").rand(100000,999999);    //订单号
    $pay_applydate = date("Y-m-d H:i:s");  //订单时间
    $pay_bankcode = "***";   //通道编码，商户后台【API管理-查看通道费率】中获取
    $pay_notifyurl = "http://youdomain/notify.php";   //服务端返回地址
    $pay_callbackurl = "http://yourdomain/callback.php";  //页面跳转返回地址
    $Md5key = "xwt1nws********inc2p4a";   //apikey(密钥)，商户后台【API管理-API开发文档】中获取
    $url = "http://www.51bugu.cc/Pay_Index";   //网关提交地址

    $params = array(
        "pay_memberid" => $pay_memberid,
        "pay_orderid" => $pay_orderid,
        "pay_amount" => $pay_amount,
        "pay_applydate" => $pay_applydate,
        "pay_bankcode" => $pay_bankcode,
        "pay_notifyurl" => $pay_notifyurl,
        "pay_callbackurl" => $pay_callbackurl,
    );

    ksort($params);
    $md5str = "";
    foreach ($params as $key => $val) {
        $md5str = $md5str . $key . "=" . $val . "&";
    }
    $sign = strtoupper(md5($md5str . "key=" . $Md5key));
    $params["pay_md5sign"] = $sign;
    $params["pay_productname"] = '嘉联支付';//交易内容描述
    $params["pay_attach"] = ''; //商户自定义参数
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $ans = curl_exec($ch);
    curl_close($ch);
    $ans = json_decode($ans, true);
    if($ans['status']=='00')
    {
        @header("location:{$ans['url']}");
    }
    else {
       die($ans['msg']);
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>支付Demo</title>
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
            <form class="form-inline" method="post" action="" style="text-align: center;padding-top:30px;">
                <h3>嘉联支付</h3>
                <input type="tel" name="amount" value="" placeholder="请输入支付金额"><br>
                <button type="submit" class="btn btn-primary btn-lg" style="margin-top:20px;">支付</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous">
</script>
</body>
</html>