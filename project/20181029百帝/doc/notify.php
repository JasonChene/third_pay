<?php

/**
 * 二维码支付
 * 交易结果通知接收并处理
 */

function SortToString($data){
    ksort($data);
    $temp = [];
    foreach($data as $i => $v){
        if(isset($v)){
            $temp[] = $i . "=" . $v;
        }
    }
    return join("&", $temp);
}

$sysPubKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDD4eufco4/RRACZ1kREFymyadT
KtzT9B6FZDSKJQ+Z4VE/pCcNklESip/v5KrmWupbqd6jan8gudLuAVduBpUIliaq
Tjnm0yW5VOvmzRcSkFsiR2L9Q2PgiI0dsSgvF4LTasRk2EKvA6YksPQ5u8sYdAsq
4upOGd070d1H2B7OqwIDAQAB
-----END PUBLIC KEY-----";
//获取POST数据
$rsp = $_POST;
$code = $rsp['code'];
//保留sign
$rspSign = $rsp['sign'];
unset($rsp['sign']);
//code为1并且status为1再进行验签
if($code == "1" && $rsp['data']['status'] == "1")
{
    $rspData = $rsp['data'];
    //data排序
    $rspData = SortToString($rspData);
    $rsp['data'] = $rspData;
    //所有的排序
    $rsp = SortToString($rsp);
    //验证签名
    $verify = openssl_verify($rsp, base64_decode($rspSign), $sysPubKey);
    //业务处理
    
    //最后回复SUCCESS
    if($verify == TRUE) {
        echo "SUCCESS";
    }
}else {
    echo "FAILED";
}