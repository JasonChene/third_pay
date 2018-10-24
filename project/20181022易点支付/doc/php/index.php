<?php

/**
 * 客户端请求本接口 实现支付
 */
include('./config.php');
$ddh = time(); //商户订单号
//记录订单号及订单状态
file_put_contents('./ddh.txt', $ddh . '|0');

$eddesc = 'test';
if ($_REQUEST['eddesc']) {
    $eddesc = $_REQUEST['eddesc'];
}

if ($_REQUEST['edfee']) {
    $edfee = $_REQUEST['edfee'];
}

if ($_REQUEST['edpay']) {
    $edpay = $_REQUEST['edpay'];
}

$data = array(
    "edid" => $edid, //商户号
    "edddh" => $ddh, //商户订单号
    "eddesc" => $eddesc, //商品名
    "edfee" => $edfee, //支付金额 单位元
    "edattch" => 'mytest', //附加信息
    "ednotifyurl" => $notifyUrl, //异步回调 , 支付结果以异步为准
    "edbackurl" => $backUrl, //同步回调 不作为最终支付结果为准，请以异步回调为准
    "edpay" => $edpay, //支付类型 此处可选项以网站对接文档为准 微信扫码：wxsm   微信wap：wxwap  支付宝扫码：zfbsm   支付宝wap：zfbwap 等参考API
    "edip" => getClientIP(0, true), //支付端ip地址
);
$data["edsign"] = md5($data["edid"] . $data["edddh"] . $data["edfee"] . $data["ednotifyurl"] . $edkey); //加密
$r = getHttpContent($edgetway, "POST", $data);
$backr = $r;
$r = json_decode($r, true); //json转数组

if (empty($r))
    exit(print_r($backr)); //如果转换错误，原样输出返回


//验证返回信息
if ($r["status"] == 1) {
    header('Location:' . $r["payurl"]); //转入支付页面
    exit();
} else {
    //echo $r['error'].print_r($backr); //输出详细信息
    echo $r['error']; //输出错误信息
    exit();
}
?>