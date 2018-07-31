<?php

header("Content-type: text/html; charset=utf-8");


$pay_memberid = "10015";   //商户ID
$pay_orderid = $_POST["orderid"];    //订单号
$pay_amount =  $_POST["amount"];    //交易金额
$pay_service= $_POST["channel"];   //银行编码
$pay_witchbank= $_POST["witchbank"];   //选择银行
if(empty($pay_memberid)||empty($pay_amount)||empty($pay_service)){
    die("信息不完整！");
}
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_notifyurl = "https://www.agpay88.com/demo/server.php";   //服务端返回地址

$pay_callbackurl = "https://www.agpay88.com/demo/page.php";  //页面跳转返回地址

$Md5key = "b0vvihp0ualbfut62a8z25quvrz2pfyd";   //密钥
$tjurl = "https://www.agpay88.com/Pay_Index.html";   //提交地址


//扫码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_service" => $pay_service,
    //"pay_bankcode" => '10002',
    "pay_bankcode" => $pay_witchbank,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl,
);

ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    if (!empty($val)){
        $md5str = $md5str . $key . "=" . $val . "&";
    }
}

$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"] = $sign;
$native['pay_attach'] = "1234|456";
$native['pay_productname'] ='VIP基础服务';

function request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
$resjson=request($tjurl,$native);

if ($native['pay_service'] == '904' || $native['pay_service'] == '907' || $native['pay_service'] == '911'){
    if($resjson){
        $resobj=json_decode($resjson);
        if($resobj->status==='success'){
            if ($resobj->type ===2){
                header('Location: '.$resobj->data);
            } else {
                echo $resobj->data;
            }
        }else if($resobj->status==='error'){
            die($resobj->msg);
        }else{
            die('未知错误!');
        }
    }else{
        die('未知错误!');
    }
} else if ($native['pay_service'] == '905'){
    if($resjson){
        $resobj=json_decode($resjson);
        if($resobj->status==='success'){
            header('Location: '.$resobj->data);
        }else if($resobj->status==='error'){
            die($resobj->msg);
        }else{
            die('未知错误!');
        }
    }else{
        die('未知错误!');
    }
} else if($native['pay_service'] == '913'){
    $resobj=json_decode($resjson);
    if($resobj->status==='success'){
        echo $resobj->data;
        die;
        //header('Location: '.$resobj->data);
    }else if($resobj->status==='error'){
        die($resobj->msg);
    }else{
        die('未知错误!');
    }
} else {
    if($resjson){
        $resobj=json_decode($resjson);
        if($resobj->status==='success'){
            $qrcode = $resobj->data;
            //header('Location: '.$resobj->data);
        }else if($resobj->status==='error'){
            die($resobj->msg);
        }else{
            die('未知错误!');
        }
    }else{
        die('未知错误!');
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>收银台</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        li{list-style: none;}
        .header{width:100%; height:60px;background: #fefefe;border-bottom: 2px solid #f68452;}
        .header .title{width: 1000px;margin: 0 auto;position: relative;}
        .header .title .scan_code{display:none;width:190px;height:190px;position: absolute;right: 0px;top: 52px;background: #ffffff;border: 1px solid #dcdcdc;box-shadow:0 0 7px rgba(115, 115, 115, .2);-webkit-box-shadow:0 0 7px rgba(115, 115, 115, .2);-moz-box-shadow:0 0 7px rgba(1115, 115, 115, .2);}
        .header .title .scan_code img{width: 160px;height: 160px;padding: 15px;}
        .header .title .logo{font-family: "æ–¹æ­£æ­£é»‘ç®€ä½“";font-size:22px;color:#000000;float:left;background: url(img/icon_logo.png) no-repeat left center;display:inline-block;height: 30px;margin-top: 17px;padding-left: 126px;}
        .header .title .logo span{font-size: 24px;color: #9f9f9f;font-family: "å¾®è½¯é›…é»‘";background: url(img/syt_03.png) no-repeat 8px 4px;display: block;width: 72px;height:30px;}
        .header .title .right{float:right; padding-top:16px;}
        .header .title .right ul{float:right; padding-top:7px;}
        .header .title .right li{float:left; padding-left:15px;font-size:12px;line-height: 17px;height: 17px;}
        .header .title .right li span{display:inline-block;color:#868686; background-repeat:no-repeat; background-image:url(img/icon_header.png);letter-spacing: 1px;}
        .icon_info{padding-left:21px; background-position:left top;line-height: 13px;}
        .icon_qq{padding-left:22px; background-position:left -13px;}
        .icon_phone{padding-left:21px; background-position:left -30px;}
        .login{padding-left: 15px;}
        .iap_new img{margin-left: 8px;float: right;margin-top: 1px;}
        .iap_new:hover .scan_code{display: block;}
        .list-inline > li {
            margin: 5px;
            padding:5px;
            width:300px;
            position: relative;
            font-size:1.2em;
        }
    </style>
<body style="background-color: #ecedf2;">
<!--<div class="header clearfix">
    <div class="title">
        <p class="logo">
            <span>|</span>
        </p>
        <div class="right">
            <div class="clearfix">
                <ul class="clearfix">
                    <li><span class="icon_phone">4008000888</span>
                    </li>
                    <li><span class="icon_qq">4008000888</span>
                    </li>
                    <li class="iap_new"><span class="icon_info">爱贝信息</span>
                        <img src="https://web.iapppay.com/s/pc/n//1.7/img/iap_new.png" alt="">
                        <div class="scan_code"><img src="" alt=""></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>-->
<!--收银台-->
<div class="container" style="background-color:#fff;padding:15px; margin-top: 15px;">
    <div class="row">
        <!--交易信息-->
        <div class="col-md-12">
            <ul class="list-inline">
                <li><strong>订单金额：<span class="text-danger"><?=$native['pay_amount']?></span>&nbsp;&nbsp;元</strong></li>
                <li><strong>商品名称：</strong><?=$native['pay_productname']?></li>
                <li><strong>订单编号：</strong><?=$native['pay_orderid']?></li>
                <li><strong>交易币种：</strong>人民币</li>
                <li><strong>交易时间：</strong><?=$native['pay_applydate']?></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <!--交易信息-->
        <div class="col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#weixin" aria-controls="weixin" role="tab" data-toggle="tab">
                        QQ钱包支付</a></li>
            </ul>
            <form action="" autocomplete="off" role="form" method="post">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="weixin">
                        <table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody><tr>
                                <td width="70" align="center">&nbsp;</td>
                                <td width="880" align="center"><table border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tbody><tr>
                                            <td align="center"><br>
                                                扫一扫付款（元）</td>
                                            <td width="250" rowspan="3" align="center" valign="top"><br>
                                                <img src="/Public/Front/img/logo-qqpay.jpeg" width="204">
                                                <br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;说明：QQ钱包支付金额必须大于5元<br></td>
                                        </tr>
                                        <tr align="center">
                                            <td height="20"><strong><font style="font-size:30px; color:#F60;"><?=$native['pay_amount']?></font>&nbsp;&nbsp;</strong></td>
                                        </tr>
                                        <tr align="center">
                                            <td><table width="100%" border="0" cellspacing="5" cellpadding="0" style="border: 1px solid #E7EAEC; ">
                                                    <tbody><tr>
                                                        <td align="center">
                                                            <img src="outputqr.php?code=<?=$qrcode?>" width="230" height="230">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody><tr>
                                                                    <td height="30" align="center"><img src="/Public/Front/img/saoyisao.png" width="14" height="14"></td>
                                                                    <td align="center"><font style="font-size:14px; color:#F60;"><strong>打开手机QQ钱包，扫一扫付款</strong></font></td>
                                                                </tr>
                                                                </tbody></table></td>
                                                    </tr>
                                                    </tbody></table></td>
                                        </tr>
                                        </tbody></table></td>
                            </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
    </div>
</div>
<!--收银台-->
<script src="/Public/Front/js/jquery.min.js"></script>
<script src="/Public/Front/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        var r = window.setInterval(function () {
            $.ajax({
                type: 'POST',
                url: 'https://www.agpay88.com/Pay_Pay_checkstatus.html',
                data: "orderid=<?=$native['pay_orderid']?>",
                dataType: 'json',
                success: function (str) {
                    if (str.status == "ok") {
                        $("#ewm").attr("src", "/Uploads/successpay.png");
                        window.clearInterval(r);
                        window.location.href = str.callback;
                    }
                }
            });
        }, 2000);
    });
</script>
</body>
</html>
