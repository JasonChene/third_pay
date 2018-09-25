<?php
namespace wmf;

//支付网关地址，一般不需要修改，或者根据文档修改。
$gateway_url = 'https://pay.szsdfm.top:444/api/submit.php';
/**
 * @param: $array为待签名数组。
 * @param: $key为商户密钥。
 * @return 签名结果
 */
function getSign($array = array(), $key2,$sign_type = 'MD5'){
    ksort($array);
    foreach ($array as $key => $value){
        if($array[$key] == '' || $key == 'sign' || $key == 'sign_type' || $key == 'key'){
            unset($array[$key]);
        }
    }
    $str = createLinkstring2($array);
    switch ($sign_type){
        case 'md5':
        case 'MD5':
            return md5($str.$key2);
            break;
        default:
            return md5($str.$key2);
            break;
    }
}

/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring2($para) {
    $arg  = "";
    foreach ($para as $key => $value){
        $arg .= $key."=".$value."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg,0,count($arg)-2);

    //如果存在转义字符，那么去掉转义
    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

    return $arg;
}
