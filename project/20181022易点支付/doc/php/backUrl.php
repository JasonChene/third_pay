<?php

/**
 * 客户端请求本接口 同步回调
 */
session_start();
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
    file_put_contents('./demo.txt', '同步：' . serialize($_REQUEST) . "\r\n", FILE_APPEND);

if ($edsign == $mysign) {
    if ($edstatus == '1') {//支付成功
        //支付成功 转入支付成功页面
        echo 'success';
    } else { //支付失败
        echo 'fail';
    }
    exit();
} else {
    /** 判断订单是否已经支付成功 如果不成功等待10秒刷新* */
    $ddhft = $_SESSION['ddhft']; //订单刷新次数

    //注意*******************************************
    //此处需要验证订单号是否支付成功 根据对接网站数据结构查询订单状态
    //判断订单状态
    $file = file_get_contents('./ddh.txt');
    $tmp = explode('|', $file);
    $ddh = $tmp[0];
    $status = $tmp[1];
    //注意*******************************************

    if ($status == 1) { //订单状态是否支付成功 $buffer['status']需要根据实际情况修改
        //跳转到支付成功后的页面
        echo 'success';
    } else {
        //支付失败等待刷新验证
        //完善流程 刷新3次跳出刷新
        if (!empty($ddhft) && $ddhft > 2) {
            $ddhft = empty($ddhft) ? 1 : $ddhft + 1;
            $_SESSION['ddhft'] = $ddhft;
            exit('支付失败');
        }

        echo '请等待支付结果返回,预计<span id="times">10</span>秒后跳转';
        echo "<script>function ShowCountDown(){var time=document.getElementById('times').innerHTML;if(parseInt(time)<=1){location.href='" . $backurl . "';}else{time=parseInt(time)-1;document.getElementById('times').innerHTML=time; window.setTimeout(function(){ShowCountDown();}, 1000);} } window.setTimeout(function(){ShowCountDown();}, 1000); </script>";
    }

    exit();
}
?>