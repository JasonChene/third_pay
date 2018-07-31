<?php
$pay_orderid = 'E'.date("YmdHis").rand(100000,999999);    //订单号
?>
<!DOCTYPE html>
<html lang=zh>
<head>
    <meta charset=UTF-8>
    <title>聚合收银台</title>
    <link href="cashier.css" rel="stylesheet">
    <script src="jquery.min.js"></script>
    <style>
        .banks{display: none}
        .banks li{position:relative;float: left;width:240px;padding-top: 5px}
    </style>
</head>
<body>
<div class="tastesdk-box">
    <div class="header clearfix">
        <div class="title">
            <p class="logo">
                <span>收银台</span>
            </p>
            <div class="right">
                <div class="clearfix">
                    <ul class="clearfix">

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="main">
        <div class="typedemo">
            <div class="demo-pc">
                <div class="pay-jd">
                    <form action="index1.php" method="post" autocomplete="off">
                        <input type="hidden" name="orderid" value="<?php echo $pay_orderid;?>">
                   
                        <div class="two-step">
                            <p><strong>请您及时付款，以便订单尽快处理！</strong>请您在提交订单后<span>24小时</span>内完成支付，否则订单会自动取消。</p>
                            <ul class="pay-infor">
                                <li>商品名称：测试应用-支付功能体验(非商品消费)</li>
                                <li>支付金额：<strong><input type="" name="amount" value="10"> <span>元</span></strong></li>
                                <li>订单编号：<span><?php echo $pay_orderid;?></span></li>
                            </ul>
                            <h5>选择支付方式：</h5>
                            <ul class="pay-label">

                                <li>
                                    <input value="904" checked="checked" name="channel" id="zfbh5" type="radio">
                                    <label id="zfbh5zf" for="zfbh5"><img src="zhifubao.png" alt="支付宝H5"><span>支付宝H5</span></label>
                                </li>

                                <li>
                                    <input value="903" checked="checked" name="channel" id="zfb" type="radio">
                                    <label id="zfbzf" for="zfb"><img src="zhifubao.png" alt="支付宝"><span>支付宝扫码</span></label>
                                </li>
                                <!--
                                <li>
                                    <input value="902" name="channel" id="wx" type="radio">
                                    <label id="wxzf" for="wx"><img src="weixin.png" alt="微信支付"><span>微信扫码</span></label>
                                </li>-->
                                <li>
                                    <input value="905" checked="checked" name="channel" id="qqh5" type="radio">
                                    <label id="qqpayh5" for="qqh5"><img src="qq.jpeg" alt="qqH5支付"><span>qqH5</span></label>
                                </li>

                                <li>
                                    <input value="908" checked="checked" name="channel" id="qqzf" type="radio">
                                    <label id="qq" for="qqzf"><img src="qq.jpeg" alt="qq扫码"><span>qq扫码</span></label>
                                </li>

<!--                                <li>-->
<!--                                    <input value="910"  name="channel"  type="radio" id="jdzf">-->
<!--                                    <label id="jd" for="jdzf"><img src="jd.jpeg" alt="京东支付"><span>京东支付</span></label>-->
<!--                                </li>-->

                                <li>
                                    <input value="907" name="channel" id="bd" type="radio">
                                    <label id="ylzf" for="bd"><img src="yinlian.png" alt="网银支付"><span>网银支付</span></label>
                                </li>


                                                                <li>
                                                                    <input value="911" name="channel" id="kjbd" type="radio">
                                                                    <label for="kjbd"><img src="yinlian.png" alt="银联支付"><span>快捷支付</span></label>
                                                                </li>
                                                                <li>
<!--                                    <input value="904"  name="channel" id="zfb1" type="radio">-->
<!--                                    <label id="zfbsjzf" for="zfb1"><img src="zhifubao.png" alt="支付宝"><span>支付宝手机</span></label>-->
<!--                                </li>-->
                            </ul>
                            <ul class="banks">
                                <li><label for="b1"><input id="b1" type="radio" value="10001" name="witchbank" checked>工商银行</label></li>
                                <li><label for="b2"><input id="b2" type="radio" value="10002" name="witchbank">农业银行</label></li>
                                <li><label for="b3"><input id="b3" type="radio" value="10003" name="witchbank">中国银行</label></li>
                                <li><label for="b4"><input id="b4" type="radio" value="10004" name="witchbank">建设银行</label></li>
                                <li><label for="b5"><input id="b5" type="radio" value="10005" name="witchbank">交通银行</label></li>
                            </ul>
                            <ul class="banks">
                                <li><label for="b6"><input id="b6" type="radio" value="10006" name="witchbank">招商银行</label></li>
                                <li><label for="b7"><input id="b7" type="radio" value="10007" name="witchbank">广东发展银行</label></li>
                                <li><label for="b8"><input id="b8" type="radio" value="10008" name="witchbank">中信银行</label></li>
                                <li><label for="b9"><input id="b9" type="radio" value="10009" name="witchbank">民生银行</label></li>
                                <li><label for="b10"><input id="b10" type="radio" value="10010" name="witchbank">光大银行</label></li>
                            </ul>
                            <ul class="banks">
                                <li><label for="b11"><input id="b11" type="radio" value="10011" name="witchbank">平安银行</label></li>
                                <li><label for="b12"><input id="b12" type="radio" value="10012" name="witchbank">上海浦东发展银行</label></li>
                                <li><label for="b13"><input id="b13" type="radio" value="10013" name="witchbank">中国邮政储蓄银行</label></li>
                                <li><label for="b14"><input id="b14" type="radio" value="10014" name="witchbank">华夏银行</label></li>
                                <li><label for="b15"><input id="b15" type="radio" value="10015" name="witchbank">兴业银行</label></li>
                            </ul>
                            <ul class="banks">
                                <li><label for="b16"><input id="b16" type="radio" value="10016" name="witchbank">北京银行</label></li>
                                <li><label for="b17"><input id="b17" type="radio" value="10017" name="witchbank">上海银行</label></li>
                            </ul>
                            <div class="btns" style="margin-top:900px;z-index: auto">
                                <button type="submit" class="pcdemo-btn sbpay-btn">立即支付</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $("#ylzf").click(function () {
        $('.banks').show();
    });
    $("#zfbzf,#wxzf,#zfbsjzf,#kjbd,#qq,#jd,#qqpayh5,#zfbh5zf").click(function () {
        $('.banks').hide();
    });
</script>
</html>
