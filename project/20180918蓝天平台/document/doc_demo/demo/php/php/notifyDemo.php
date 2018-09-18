<?php
$data = file_get_contents("php://input");

function paraFilters($para) {
    $para_filter = array();
    while (list ($key, $val) = each ($para)) {
        if($key == "sign" || $val == "")continue;
        else    $para_filter[$key] = $para[$key];
    }
    return $para_filter;
}

function argSorts($para) {
    ksort($para);
    reset($para);
    return $para;
}

function local_sign($datas = array(), $key = ''){
    $str = http_build_query(argSorts(paraFilters($datas)));
    $sign = md5($str."&key=".$key);
    return $sign;
}




header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
include '../../include/header.inc.php';




$appkey = "9e37a55147d58c23c12db6a7e7a9c2d3";

$params = array(
    "mch_id" => $data["mch_id"], 
    "payment_time" => $data["payment_time"], 
    "total_fee" => $data["total_fee"],
    "trade_no" => $data["trade_no"], 
    "out_trade_no" => $data["out_trade_no"]
);

$sign = local_sign($params, $appkey);


if ($sign != $data["sign"]) {
    exit("invalid sign");
}

//app服务器处理相关事务 TODO


exit("success");