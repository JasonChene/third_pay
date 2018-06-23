<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit"/>
    <title>杉德一键快捷 1.0</title>
    <script type="text/javascript" src="scripts/jquery-1.7.2.min.js"></script>
    <link type="text/css" href="css/sand.css" rel="stylesheet"/>
</head>
<body>
<div>
    <div id="main">
        <div id="head">
            <dl class="sandpay_link">
                <a target="_blank" href="https://www.sandpay.com.cn/"><span>杉德首页</span></a>
            </dl>
            <span class="title">杉德 一键快捷产品1.0 一键快捷入口</span>
        </div>
        <div class="cashier-nav"></div>
        <div class="center">
            <form name="sandpay_ment" id="sandpay_ment" action="" method="post">
                <div id="body" style="clear:left">
                    <dl class="content">
                        <dt>[mid]商户号:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="mid" value="100211701160001"/>
                        </dd>
                        <dt>[userId]用户ID:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="userId" value="<?php echo "0000" . mt_rand(1, 100); ?>"/>
                        </dd>
                        <dt>[orderCode]订单唯一编号:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="orderCode" value="<?php echo '201709' . time() ?>"/>
                        </dd>
                        <dt>[orderTime]订单时间:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="orderTime" value="<?php echo date('YmdHis', time()) ?>"/>
                        </dd>
                        <dt>[totalAmount]订单金额:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="totalAmount" value="000000000012"/>
                        </dd>
                        <dt>[subject]订单标题:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="subject" value="测试test"/>
                        </dd>
                        <dt>[body]订单描述:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="body" value="test"/>
                        </dd>
                        <dt>[currencyCode]币种:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="currencyCode" value="156"/>
                        </dd>
                        <dt>[notifyUrl]异步通知地址:</dt>
                        <dd>
                            <span class="null-star">*</span><input type="text" name="notifyUrl"
                                                                   value="http://192.168.22.116:8086/merPayReturn/"/>
                        </dd>
                        <dt>[frontUrl]前端跳转地址:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <input type="text" name="frontUrl" value="http://192.168.22.116:8086/merPayReturn/"/>
                        </dd>
                        <dt>[clearCycle]清算模式:</dt>
                        <dd>
                            <span class="null-star">*</span>
                            <select id="clearCycle" name="clearCycle">
                                <option value="0" selected="selected">T1（默认）</option>
                                <option value="1">T0</option>
                                <option value="2">D0
                                <option>
                            </select>
                        </dd>
                        <dt>[extend]扩展域:</dt>
                        <dd>
                            <input type="text" name="extend" value=""/>
                        </dd>
                        <dt></dt>
                        <dd>
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" type="button" style="text-align:center;"
                                    onclick="Sand()">确 认</button>
                        </span>
                        </dd>
                    </dl>
                </div>
            </form>
        </div>
    </div>
</div>
<div>
    <form id="sandpay" action="https://cashier.sandpay.com.cn/fastPay/quickPay/index" method="post" hidden="hidden">
        <textarea name="charset">utf-8</textarea><br>
        <textarea name="signType">01</textarea><br>
        <textarea name="data" id="data"></textarea><br>
        <textarea name="sign" id="sign"></textarea><br>
    </form>
</div>
</body>
<script type="text/javascript">

    function Sand() {
        // 异步获取签名
        var url = "quickpay.php";
        var data = $("#sandpay_ment").serialize();
        $.ajax({
            type: "post",
            url: url,
            dataType: "json",
            data: data,
            error: function (request) {
                alert("Connection error");
            },
            success: function (r) {
                //组装报文并提交
                var data = r.data;
                var sign = r.sign;
                $("#data").text(data);
                $("#sign").text(sign);
                $("#sandpay").submit();
            }
        });
    }
</script>

</html>