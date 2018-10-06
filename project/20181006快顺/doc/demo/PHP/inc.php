<?php
/**
 * Created by PhpStorm.
 * User: 陈远
 * Date: 2018/9/12
 * Time: 14:03
 */
header('Content-Type:text/html;charset=utf8');
date_default_timezone_set('Asia/Shanghai');

//商户ID
$userid = '10888';

//商户token
$userkey = 'bf26bd90ec3b4de10b003728757d9e86f4a9ba3e';

//网关地址
$payurl = 'http://localhost:81/';

//回调地址 通知地址
$notifyurl = 'http://120.78.145.248/phpdemo/notify.php';

//接口版本(无须更改)
$version = '1.0';

function post_curls($url, $post)
{

    $curl = @curl_init(); // 启动一个CURL会话
    if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
        @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    }
    @curl_setopt($curl, CURLOPT_URL, $url);
    @curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    @curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
    @curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    @curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    @curl_setopt($curl, CURLOPT_POST, 1);
    @curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    @curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    @curl_setopt($curl, CURLOPT_HEADER, 0);
    @curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = @curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    return $res;
}