<?php
header("Content-type: text/html; charset=utf-8");

require("helper.php");

$merchantCode = $_POST["merchant_code"];
$orderNo = $_POST["order_no"];
$orderTime = $_POST["order_time"];
$orderAmount = $_POST["order_amount"];
$trade_status = $_POST["trade_status"];
$tradeNo = $_POST["trade_no"];
$returnParams = $_POST["return_params"];
$sign= $_POST["sign"];


$kvs = new KeyValues();
$kvs->add("merchant_code", $merchantCode);
$kvs->add("order_no", $orderNo);
$kvs->add("order_time", $orderTime);
$kvs->add("order_amount", $orderAmount);
$kvs->add("trade_status", $trade_status);
$kvs->add("trade_no", $tradeNo);
$kvs->add("return_params", $returnParams);
$_sign = $kvs->sign();

echo $_sign."     ".$sign; 

if ($_sign == $sign)
{
    if ($trade_status == "success")
    {
        $tradeResult = "success";
        //这个success字符串在支付成功的情况下必须填入，因为交易平台回调商户的后台通知地址后，会通过返回的内容中包含success来判别商户是否收到通知，并成功告知交易平台。
        //这个success字符串只有在商户后台通知时必须填写，页面通知可不填写。
    }
    else
    {
        $tradeResult = "fail";
    }
}
else
{
    $tradeResult = "不合法数据";
}

$orderNo = $_POST["order_no"];
$orderAmount = $_POST["order_amount"];
$orderTime = $_POST["order_time"];

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
<div style="text-align: center; padding-top: 20px;">
    <h1>支付结果</h1>
</div>
<div style="text-align: center; font-size: 20px; color: red;">
    <strong><?=$tradeResult?></strong>
</div>
<div>
    <table align="center" cellpadding="2" style="margin-top: 5px;">
        <tr>
            <td>
                订单号：
            </td>
            <td>
                <?=$orderNo?>
            </td>
        </tr>
        <tr>
            <td>
                订单金额：
            </td>
            <td>
                <?=$orderAmount?>
            </td>
        </tr>
        <tr>
            <td>
                订单时间：
            </td>
            <td>
                <?=$orderTime?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
