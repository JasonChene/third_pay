<?php

/**
 * 客户端请求本接口 异步回调
 * author: xinyi
 * Date: 2017/10/7
 */
include('./config.php');
$fxid = $_REQUEST['fx_merchant_id']; //商户编号
$fxddh = $_REQUEST['fx_order_id']; //商户订单号
$fxorder = $_REQUEST['fx_transaction_id']; //平台订单号
$fxdesc = $_REQUEST['fx_desc']; //商品名称
$fxfee = $_REQUEST['fx_order_amount']; //交易金额
$fxattch = $_REQUEST['fx_attch']; //附加信息
$fxstatus = $_REQUEST['fx_status_code']; //订单状态
$fxtime = $_REQUEST['fx_time']; //支付时间
$fxsign = $_REQUEST['fx_sign']; //md5验证签名串

$mysign = md5(md5($fxid . $fxddh . $fxorder . $fxfee . $fxkey . $fxstatus));

//记录回调数据到文件，以便排错
if ($fxloaderror == 1)
    file_put_contents('./demo.txt', '异步：' . serialize($_REQUEST) . "\r\n", FILE_APPEND);

if ($fxsign == $mysign) {
    if ($fxstatus == '200') {//支付成功
        //支付成功 更改支付状态 完善支付逻辑
        $file = file_get_contents('./ddh.txt');
        $tmp = explode('|', $file);
        $ddh = $tmp[0];
        $status = $tmp[1];
        if($fxddh==$ddh) file_put_contents('./ddh.txt',$ddh.'|1');
        echo 'success';
    } else { //支付失败
        echo 'fail';
    }
} else {
    echo 'sign error';
}
?>