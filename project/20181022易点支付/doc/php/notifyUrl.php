<?php

/**
 * 客户端请求本接口 异步回调
 */
include('./config.php');
$edid = $_REQUEST['edid']; //商户编号
$edddh = $_REQUEST['edddh']; //商户订单号
$edorder = $_REQUEST['edorder']; //平台订单号
$eddesc = $_REQUEST['eddesc']; //商品名称
$edfee = $_REQUEST['edfee']; //交易金额
$edattch = $_REQUEST['edattch']; //附加信息
$edstatus = $_REQUEST['edstatus']; //订单状态
$edtime = $_REQUEST['edtime']; //支付时间
$edsign = $_REQUEST['edsign']; //md5验证签名串

$mysign = md5($edstatus . $edid . $edddh . $edfee . $edkey); //验证签名
//记录回调数据到文件，以便排错
if ($edloaderror == 1)
    file_put_contents('./demo.txt', '异步：' . serialize($_REQUEST) . "\r\n", FILE_APPEND);

if ($edsign == $mysign) {
    if ($edstatus == '1') {//支付成功
        //支付成功 更改支付状态 完善支付逻辑
        $file = file_get_contents('./ddh.txt');
        $tmp = explode('|', $file);
        $ddh = $tmp[0];
        $status = $tmp[1];
        if ($edddh == $ddh)
        file_put_contents('./ddh.txt', $ddh . '|1');
        echo 'success';
    } else { //支付失败
        echo 'fail';
    }
} else {
    echo 'sign error';
}
?>