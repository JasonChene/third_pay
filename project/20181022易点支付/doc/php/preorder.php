<?php

/**
 * 客户端请求本接口 获取订单信息
 */
include('./config.php');
$ddh = ''; //需要查询的订单号
$data = array(
    "edid" => $edid, //商户号
    "edddh" => $ddh, //商户订单号
    "edaction" => "orderquery"//查询动作
);

$data["edsign"] = md5($data["edid"] . $data["edddh"] . $data["edaction"] . $edkey); //加密
$r = file_get_contents($edgetway . "?" . http_build_query($data));
$backr = $r;
$r = json_decode($r, true); //json转数组
if ($r['edstatus'] == 1) {
    //支付成功
    exit('支付成功');
} else {
    //支付失败
    //exit(print_r($backr)); //返回的详细信息
    exit($r['error']); //返回的错误信息
}
?>