<?php
$merchantNo = '你的商户id';
$key = '商户密钥';

$params = $_REQUEST;
$arr = $params;
unset($arr['sign']);
ksort($arr);
$buff = "";
foreach ($arr as $x => $x_value){
    if($x_value != '' && !is_array($x_value)){
        $buff .= "{$x}={$x_value}&";
    }
}
$buff.="key={$key}";
$sign = strtoupper(md5($buff));
if($sign == strtoupper($params['sign'])) {
    //处理已支付状态逻辑
    echo 'SUCCESS';
} else {
    echo 'FAIL';
}