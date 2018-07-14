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
                        <li <?php if(isset($_GET['cur']) && $_GET['cur'] == 2) {?>class="cur" <?php }?> href="orderQuery.php?cur=2">查询测试</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="auto_center" id="auto_center">
            <form action="request.php?action=submitOrderInfo"  class="ajax-form">
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
            </span>
                        <i>*</i><em>长度32</em>
                    </div>

                    <div class="form_list">
                        <span class="list_title">商品描述：</span>
                        <span class="list_val">
                <input name="ord_name" value="测试购买商品" maxlength="64" size="64" placeholder="长度127">
            </span>
                        <i>*</i><em>长度64</em>
                    </div>

                    <div class="form_list">
                        <span class="list_title">支付金额：</span>
                        <span class="list_val">
                <input name="trade_amount" value="1" placeholder="单位：分">
            </span>
                        <i>*</i><em>单位：分 整型</em>
                    </div>

                    <div class="form_list">
                        <span class="list_title">支付类型：</span>
                        <span class="list_val">
                <input name="paychannel_type"  value="" >
            </span>
                        <i>*</i><em>支付类型:</em>
                    </div>
                    <div class="form_list">
                        <span class="list_title">接口类型：</span>
                        <span class="list_val">
                    <select name="interface_type" id="interface_type">
                        <option value="1">裸接口</option>
                        <option value="2">收银台</option>
                    </select>
            </span>
                        <i>*</i><em>接口类型</em>
                    </div>
                    <div class="form_list">
                        <span class="list_title">异步地址：</span>
                        <span class="list_val">
                <input name="merchant_notify_url" value="" placeholder="">
            </span>
                        <i>*</i><em>异步地址</em>
                    </div>
                    <div class="form_list">
                        <span class="list_title">终端IP：</span>
                        <span class="list_val">
                <input name="client_ip" vtype="ip" value="127.0.0.1" maxlength="16" placeholder="长度16">
            </span>
                        <i>*</i><em>长度16</em>
                    </div>
                    <div class="form_list">
                        <span class="list_title">同步跳转地址：</span>
                        <span class="list_val">
                <input name="merchant_return_url" value="" placeholder="">
            </span>
                        <i></i><em>同步跳转地址</em>
                    </div>


                    <div class="form_list">
                        <span class="list_title">银行编码：</span>
                        <span class="list_val">
                <input name="bank_code" value="" placeholder="">
            </span>
                        <i></i><em>银行编码</em>
                    </div>


                    <div class="form_list">
                        <span class="list_title"></span>
                        <span class="list_val submit btn btn_blue ajax-form-button">确定</span>
                    </div>
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