<?php
//此文件为CSPAY支付配置文件
@header("Content-type: text/html;charset=utf-8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>交易接口演示</title>
</head>
<style>
    html,body {
        width:100%;
        min-width:1200px;
        height:auto;
        padding:0;
        margin:0;
        font-family:"微软雅黑";
    }
    .content {
        width:100%;
        min-width:1200px;
        height:400px;
        background-color:#fff;
    }
    .cspayform {
        width:800px;
        margin:0 auto;
        height:400px;
    }
    .element {
        width:600px;
        min-height:80px;
        margin-left:100px;
        font-size:20px
    }
    .etitle,.einput {
        float:left;
        height:26px
    }
    .etitle {
        width:150px;
        line-height:26px;
        text-align:right
    }
    .einput {
        width:350px;
        margin-left:20px
    }
    .einput input[type="text"] {
        width:398px;
        height:24px;
        border:1px solid #0ae;
        font-size:16px
    }
    .mark {
        margin-top: 10px;
        width:500px;
        height:30px;
        margin-left:80px;
        line-height:30px;
        font-size:12px;
        color:#999
    }
    .legend {
        margin-left:100px;
        font-size:24px
    }
    .alisubmit {
        width:400px;
        height:40px;
        border:0;
        background-color:#0ae;
        font-size:16px;
        color:#FFF;
        cursor:pointer;
        margin-left:140px
    }
    .font {
        font-size:13px;
    }
</style>
<body>
<div class="content">
    <form action="pay.php" class="cspayform" method="post">
        <div class="element" style="margin-top:60px;">
            <div class="legend">香蕉支付交易接口快速通道 </div>
        </div>
        <div class="element">
            <div class="etitle">商户订单号:</div>
            <div class="einput"><input type="text" name="WIDout_trade_no" value="<?php echo date('YmdHis').rand(111,999);?>"></div>
            <br>
            <div class="mark">注意：商户订单号(out_trade_no).必填(建议是英文字母和数字,不能含有特殊字符)</div>
        </div>

        <div class="element">
            <div class="etitle">商品名称:</div>
            <div class="einput"><input type="text" name="WIDbody" value="test商品123"></div>
            <br>
            <div class="mark">注意：产品名称(body)，必填(建议中文，英文，数字，不能含有特殊字符)</div>
        </div>
        <div class="element">
            <div class="etitle">付款金额:</div>
            <div class="einput"><input type="text" name="WIDtotal_fee" value="1.00"></div>
            <br>
            <div class="mark">注意：付款金额(total_fee)，必填(格式如：1.00,请精确到分，最少0.1)</div>
        </div>
        <div class="element">
            <div class="etitle">银行缩写:</div>
            <div class="einput"><select name="bank">
                    <option value="ICBC">工商银行</option>
                    <option value="ABC">农业银行</option>
                    <option value="BOC">中国银行</option>
                    <option value="CCB">建设银行</option>
                    <option value="CMB">招商银行</option>
                    <option value="BOCM">交通银行</option>
                    <option value="CMBC">民生银行</option>
                    <option value="CNCB">中信银行</option>
                    <option value="CEBB">光大银行</option>
                    <option value="CIB">兴业银行</option>
                    <option value="BOB">北京银行</option>
                    <option value="GDB">广发银行</option>
                    <option value="HXB">华夏银行</option>
                    <option value="PSBC">邮储银行</option>
                    <option value="SPDB">浦发银行</option>
                    <option value="PAB">平安银行</option>
                    <option value="BOS">上海银行</option>
                    <option value="HCCB">杭州银行</option>
                    <option value="ZSBK">浙商银行</option>
                    <option value="QDBK">青岛银行</option>
                    <option value="NBCB">宁波银行</option>
                    <option value="TCCB">天津银行</option>
                    <option value="LZBK">兰州银行</option>
                    <option value="NJCB">南京银行</option>
                    <option value="CDCB">成都银行</option>
                    <option value="BJRC">北京农村商业银行</option>
                    <option value="SHRB">上海农村商业银行</option>
                </select></div>
            <br>
            <div class="mark">注意：可选（网关所用）</div>
        </div>
        <div class="element" >
            <div class="etitle">付款方式:</div>
            <div class="einput font" >
                <select  name="WIDpay_sid">
                    <option value="wxpay.ma">微信扫码</option>
                    <option value="alipay.ma">支付宝扫码</option>
                    <option value="qqpay.ma">qq钱包扫码</option>
                    <option value="wxpay.wap">微信手机</option>
                    <option value="alipay.wap">支付宝手机</option>
                    <option value="qqpay.wap">qq钱包手机</option>
                    <option value="wangyin.ma">网银快捷</option>
                    <option value="wangyin.wap">网银网关</option>
                    <option value="yinlian.ma">银联扫码</option>
                    <option value="yinlian.wap">银联WAP</option>
                    <option value="wangyin.wuka">无卡支付</option>
                    <option value="jdpay.ma">京东扫码</option>
                    <option value="jdpay.wap">京东WAP</option>
                </select>

            </div>
            <br><br>
            <div class="mark">注意：付款方式，必选</div>
        </div>
        <div class="element">
            <input type="submit" class="alisubmit" value ="确认支付">
        </div>
    </form>
</div>
</body>
</html>