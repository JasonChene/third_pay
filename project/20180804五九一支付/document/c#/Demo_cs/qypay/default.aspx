<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="default.aspx.cs" Inherits="Demo_cs.qypay._default" %>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>通用支付接口</title>
     <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/qypay/css/qy_style.css">
    <link rel="stylesheet" href="/qypay/css/demo-page.css">
    <link rel="shortcut icon" href="/qypay/images/favicon.ico">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <![endif]-->
    <!-- jQuery 3 -->
    <script src="/qypay/js/jquery.min.js"></script>
    <script src="/qypay/js/bootstrap.min.js"></script>
</head>
<body class="layout-top-nav">
    <!----浏览器判断*/---->
    <div class="mask" id="mask" style="display: none">
        <div class="md-content">
            <button class="closeMask">关闭</button>
            <div class="mask-head">
                <img src="/qypay/images/logo.png" alt="">
            </div>
            <h1>请升级您的浏览器</h1>
            <h5>尊敬的windows用户，我们强烈建议您升级操作系统或浏览器，以获得更安全的网络环境和更好的用户体验</h5>
            <ul>
                <li><a href="http://www.google.cn/chrome/browser/desktop/" target="_parent">Chrome浏览器</a> </li>
                <li><a href="http://www.firefox.com.cn/" target="_parent">火狐浏览器</a> </li>
                <li><a href="http://pc.uc.cn/" target="_parent">360极速浏览器</a> </li>
            </ul>
        </div>
    </div>
    <div class="md-overlay">&nbsp;</div>
    <script src="/qypay/js/ie.js"></script>
    <!--/浏览器判断-->
  

    <div class="container">
        <h1 class="qy-title">通用支付接口</h1>
        <div class="pay-box">
            <form class="form-horizontal" id="pay-form" method="post" action="send.aspx" target="_blank">
                <div class="form-group pay-box-in">
                    <label class="col-sm-2">支付金额</label>
                    <div class="col-sm-10">
                        <input class="col-xs-10" name="payMoney" type="text" placeholder="请填写正整数金额，最低1元" check="required num">
                    </div>
                </div>
                <div class="form-group pay-box-in">
                    <label class="col-sm-2">支付方式</label>
                    <div class="col-sm-10">
                        <ul>
                            <li><input type="radio" name="ptype" value="wx" checked><label><img src="/qypay/images/wxpay.png" width="150" height="40" alt=""></label></li>
                            <li><input type="radio" name="ptype" value="al" checked><label><img src="/qypay/images/alipay.png" alt=""></label></li>
                        </ul>
                    </div>
                </div>
                <div class="btns">
                    <button type="submit" class="pcdemo-btn sbpay-btn">立即支付</button>
                </div>
            </form>
            <script src="/qypay/js/easyform.js"></script>
            <script type="text/javascript">
                $("#pay-form").checkForm();
            </script>
        </div>
    </div>

    <script type="text/javascript">
            //置顶图标显示
            $('#top-back').hide()
            $(window).scroll(function () {
                if ($(this).scrollTop() > 350) {
                    $("#top-back").fadeIn();
                }
                else {
                    $("#top-back").fadeOut();
                }
            })
            //置顶事件
            function topBack() {
                $('body,html').animate({ scrollTop: 0 }, 300);
            }
    </script>
</body>
</html>
