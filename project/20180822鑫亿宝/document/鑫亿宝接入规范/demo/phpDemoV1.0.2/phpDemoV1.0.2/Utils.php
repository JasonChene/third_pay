<?php

/**
 * Created by PhpStorm.
 * User: liuguang
 * Date: 2017/12/18
 * Time: 17:42
 */

/**
 * 设置加签数据
 *
 * @param unknown $array
 * @param unknown $md5Key
 * @return string
 */
function signMsg($array, $md5Key){
    $msg = "";
    // 转换为字符串 &key=value&key.... 加签
    foreach ($array as $key => $val) {
        // 不参与签名
        if($key != "sign"){
            $msg = $msg."&$key=$val";
        }
    }

    $msg = substr($msg,1).$md5Key;
    return  $msg;
}

function strToArr ($str){
    $arr = explode("&",$str);
    $r = array();
    foreach ($arr as $val )
    {
        $t = explode("=",$val);

        $r[$t[0]]= substr($val,strlen($t[0])+1);
    }
    return $r;
}

?>
