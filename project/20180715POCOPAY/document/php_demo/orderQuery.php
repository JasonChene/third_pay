<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>统一下单接口测试</title>
    <link href="public/css/pay.css" rel="stylesheet" type="text/css"/>
    <link href="public/css/sprite.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="public/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="public/js/pay.js"></script>
</head>
<body>
<div id="pay_platform">

    <div class="content">
        <div class="menu">
            <div class="item">
                <h5>接口测试</h5>
                <div class="">
                    <ul>
                        <li <?php $_GET['cur'] == !empty($_GET['cur'])?$_GET['cur']:1; if(isset($_GET['cur']) && $_GET['cur'] == 1) {?>class="cur" <?php }?> href="index.php?cur=1">支付测试</li>
                        <li <?php if(isset($_GET['cur']) && $_GET['cur'] == 2) {?>class="cur" <?php }?>>查询测试</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="auto_center" id="auto_center">
            <form action="request.php?action=orderQuery"  class="ajax-form">
            <div id="queryOrder">
                <div class="ico_title">查询测试</div>

                <div class="form_wrap account">
                    <div class="form_list">
                        <span class="list_title">接口名方法：</span>
                        <span class="list_val">
                <input name="service" value="" maxlength="64" size="64" placeholder="">
            </span>
                        <i>*</i><em>接口名方法</em>
                    </div>
                    <div class="form_list">
                        <span class="list_title">商户订单号：</span>
                        <span class="list_val">
                <input name="merchant_order_sn" value="" maxlength="32" size="32" placeholder="长度32">
            </span><em>长度32</em>
                    </div>

                    <div class="form_list">
                        <span class="list_title"></span>
                        <span class="list_val submit btn btn_blue ajax-form-button">确定</span>
                    </div>
                </div>
            </div>
            </form>
    </div><!-- content end -->

        <div class="auto_center result" >

        </div>

</div>
</body>
</html>