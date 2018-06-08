<?php
$merchant = '1091';
$key = 'bf6331dab67e734a7887d89a0a805537';
$out_trade_no = '1527774271';                //商户订单号
$gateway_url = 'http://ehuikatong.com/api/trade-query';

$response = query($merchant, $key, $out_trade_no, $gateway_url);
var_dump($response);
function query($merchant, $key, $out_trade_no, $gateway_url)
{
    $data['merchant'] = $merchant;
    $data['out_trade_no'] = $out_trade_no;
    $data['sign'] = sign($data, $key);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_URL, $gateway_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);

    $ret = curl_exec($ch);
    curl_close($ch);
//        print_r($data);

    return json_decode($ret, true);
}

function sign($params, $key) {
    ksort($params);
    $str = '';
    foreach ($params as $k => $value) {
        $str .= $k.'='.$value.'&';
    }
    $str .= 'key='.$key;
    return strtoupper(md5($str));
}