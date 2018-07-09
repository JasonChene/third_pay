<?php
require_once 'lib/Common.php';


//可进行测试微信扫码，QQ扫码，QQH5，银联扫码以及网关，其他暂不支持。
$OrderTime = time();

$SubmitIP = $_SERVER["REMOTE_ADDR"];

$BusinessOrders = "Senyo_$OrderTime";

$http = $_SERVER['SERVER_NAME'];
$NotifyUrl = "http://$http/lib/notify.php";

$ReturnUrl = "http://$http/lib/return.php";

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>支付测试 - 圣耀支付</title>
<style>
    * {margin:0;padding:0}
    table tr td.right {text-align:right}
    table tr td.center {text-align:center}
</style>
</head>
<body>
    <table border="1">
        <form name="SenyoForm" method="POST" action="lib/Pay.php" target="_blank">
            <tbody>
                <tr>
                    <td class="right"><span>商户号：</span></td>
                    <td><input type="text" name="Merchants" value="<?php echo $Merchants; ?>" /></td>
                </tr>
                    <td class="right"><span>测试金额：</span></td>
                    <td><input type="text" name="Amount" value="1.00" /></td>
                </tr>
                <tr>
                    <td class="right"><span>商品描述：</span></td>
                    <td><input type="text" name="Description" value="这是一个小商品" /></td>
                </tr>
                <tr>
                    <td class="right"><span>订单号：</span></td>
                    <td><input type="text" name="BusinessOrders" value="<?php echo $BusinessOrders; ?>" /></td>
                </tr>
                <tr>
                    <td class="right"><span>下单时间：</span></td>
                    <td><input type="text" name="OrderTime" value="<?php echo $OrderTime; ?>" /></td>
                </tr>
                <tr>
                    <td class="right"><span>下单地址：</span></td>
                    <td><input type="text" name="SubmitIP" value="<?php echo $SubmitIP; ?>" /></td>
                </tr>
                <tr>
                    <td class="right"><span>异步通知地址：</span></td>
                    <td><input type="text" name="NotifyUrl" value="<?php echo $NotifyUrl; ?>" /></td>
                </tr>
                <tr>
                    <td class="right"><span>支付类型：</span></td>
                    <td>
                        <label><input type="radio" name="TypeService" value="Wechat" onclick="PostS('Wechat');" checked/>微信</label>
                        <label><input type="radio" name="TypeService" value="Alipay" onclick="PostS('Alipay');" />支付宝</label>
                        <label><input type="radio" name="TypeService" value="QQ" onclick="PostS('QQ');" />QQ钱包</label>
                        <label><input type="radio" name="TypeService" value="UnionPay" onclick="PostS('UnionPay');" />银联</label>
                        <label><input type="radio" name="TypeService" value="Bank" onclick="PostS('Bank');" />网关</label>
                        <label><input type="radio" name="TypeService" value="JD" onclick="PostS('JD');" />京东钱包</label>
                        <label><input type="radio" name="TypeService" value="Baidu" onclick="PostS('Baidu');" />百度钱包</label>
                    </td>
                </tr>
                <tr>
                    <td class="right"><span>支付端口：</span></td>
                    <td id="Post">
                        <label><input type="radio" name="PostService" value="Scan" onclick="check('Scan');" checked>扫码</label>
                        <label><input type="radio" name="PostService" value="H5" onclick="check('H5');">H5</label>
                        <label><input type="radio" name="PostService" value="Card" onclick="check('Card');">刷卡</label>
                    </td>
                    <td id="Bank" style="display:none">
                        <label><input type="radio" name="PostService" value="CCB">建设银行</label>
                        <label><input type="radio" name="PostService" value="ICBC">工商银行</label>
                        <label><input type="radio" name="PostService" value="ABC">农业银行</label>
                    </td>
                </tr>
                <tr>
                    <td class="right"><span>付款码：</span></td>
                    <td id="Card" style="display:none">
                        <input type="text" name="CardCode" />
                    </td>
                </tr>
                <tr>
                    <td class="right"><span>同步通知地址：</span></td>
                    <td id="ReturnUrl" style="display:none">
                        <input type="text" name="ReturnUrl" value="<?php echo $ReturnUrl; ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center"><button type="submit">提交</button></td>
                </tr>
            </tbody>
        </form>
    </table>
<script>
    Bank = document.getElementById("Bank");
    Post = document.getElementById("Post");
    ReturnUrl = document.getElementById("ReturnUrl");
    Card = document.getElementById("Card");
    function PostS(value) {
        if(value == 'Bank') {
            Bank.style.display = "block";
            ReturnUrl.style.display = "block";
            Card.style.display = "none";
            Post.style.display = "none";
        } else {
            Bank.style.display = "none";
            ReturnUrl.style.display = "none";
            Post.style.display = "block";
        }
    }
    function check(value) {
        if(value == 'Scan') {
            ReturnUrl.style.display = "none";
            Card.style.display = "none";
        } else if(value == 'Card') {
            ReturnUrl.style.display = "none";
            Card.style.display = "block";
        } else if(value == "H5"){
            ReturnUrl.style.display = "block";
            Card.style.display = "none";
        }
    }
</script>
</body>
</html>