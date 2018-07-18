<?php


header("Content-type: text/html; charset=utf-8");

require("helper.php");

$orderNo = rand(10000000, 99999999);
$orderAmount = round((rand() / getrandmax()) * 10000) / 100;
$currentDate = date(DATE_TIME_FORMAT,time());
?>

<!DOCTYPE html>
<html>
<head>
    <title>代付</title>
    <link type="text/css" rel="stylesheet" href="static/bootstrap-2.3.2/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="static/css/radio-banks.css"/>
    <link type="text/css" rel="stylesheet" href="static/css/simple-radius-border.css"/>
    <script type="text/javascript" src="static/jquery/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="static/bootstrap-2.3.2/js/bootstrap.min.js"></script>
</head>
<body>
<form id="mainForm" class="form-horizontal" action="/Demo_php/remitSubmit.php" method="POST">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span10 offset1">
                <div style="margin-top: 4px;">
                    <!--<img src="static/imgs/head-logo.jpg" class="img-rounded">-->
                </div>
                <ul class="nav nav-pills nav-stacked" style="margin-top: 2px; margin-bottom: -1px;">
                    <li class="active">
                        <a href="#" style="font-size: 18px;">订单信息</a>
                    </li>
                </ul>
                <div class="low-half-radius-border" style="padding-top: 10px;">
                <div class="control-group">
                        <label class="control-label" style="width: 100px; font-size: 16px;"><strong>收款人：</strong></label>
                        <div class="controls" style="margin-left: 110px; padding-top: 5px;">
                            <span>
                            <input type="text" name="account_name" id="account_name" />
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="width: 100px; font-size: 16px;"><strong>收款卡号：</strong></label>
                        <div class="controls" style="margin-left: 110px; padding-top: 5px;">
                            <span>
                            <input type="text" name="account_number" id="account_number" />
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="width: 100px; font-size: 16px;"><strong>收款银行名称：</strong></label>
                        <div class="controls" style="margin-left: 110px; padding-top: 5px;">
                            <span>
                            <input type="text" name="bank_code" id="bank_code" />
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="width: 100px; font-size: 16px;"><strong>代付单号：</strong></label>
                        <div class="controls" style="margin-left: 110px; padding-top: 5px;">
                            <span>
                            <input type="text" name="trade_no" id="trade_no" value="<?=$orderNo?>" />
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="width: 100px; font-size: 16px;"><strong>金额：</strong></label>
                        <div class="controls" style="margin-left: 110px; padding-top: 5px;">
                            <span style="color: red;"><strong><input type="text" name="order_amount" id="order_amount" value="<?=$orderAmount?>" />元</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span10 offset1">
                <div style="border-left: 1px solid #ddd; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; margin-top: -20px;">
                    <div class="container-fluid" style="padding-top:20px;">
                           
                        <div class="row-fluid" style="padding-left: 20px; margin-bottom: 10px;">
                            <button id="submitBtn" class="btn btn-large btn-primary" type="button">确认支付</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="merchant_code" value="<?=MER_NO?>"><!-- 商户号 -->
	<input type="hidden" name="order_time" value="<?=$currentDate?>"><!--时间-->
	
</form>
<script type="text/javascript">
    $(function () {
        var bankCode = $('input[name="bank_code"]');
        $('#submitBtn').click(function () {
                $('#mainForm').submit();
        });
    });
</script>
</body>
</html> 
