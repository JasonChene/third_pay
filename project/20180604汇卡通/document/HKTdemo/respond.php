<?php
$merchant = '1091';
$key = 'bf6331dab67e734a7887d89a0a805537';
NotifyURL($merchant, $key);
function NotifyURL($merchant, $key)
{
    $data['status'] = $_POST['status'];
    $data['out_trade_no'] = $_POST['out_trade_no'];
    $data['merchant'] = $merchant;
    $data['order_id'] = $_POST['order_id'];
    $data['total_amount'] = $_POST['total_amount'];
    if(sign($data, $key) == $_POST['sign']){
        //更新订单等逻辑
        echo 'success'; //输出success
    }
}

function sign($params, $key) {
    ksort($params);
    $str = '';
    foreach ($params as $k => $value) {
        $str .= $k.'='.$value.'&';
    }
    $str .= 'key='.$key;
    echo strtoupper(md5($str));
}