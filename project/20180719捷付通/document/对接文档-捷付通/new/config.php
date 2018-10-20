<?php

/**
 * 客户端配置信�?
 * author: fengxing
 * Date: 2017/10/7
 */
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");

$notifyUrl= "http://".$_SERVER['HTTP_HOST']."/jft/notifyUrl.php"; //异步回调地址，外网能访问
$backUrl  = "http://".$_SERVER['HTTP_HOST']."/jft/backUrl.php";   //同步回调地址，外网能访问


$fxid = "2017100"; //商户ID
$fxkey = "ZVFjVNoCFluOoYcpzPUtYIIRsZVPilhC"; //商户秘钥key 从用户后台获秘钥
$fxgetway = "http://www.8jft.com/Pay"; //网关

/*

$fxid = "2018185"; //商户ID
$fxkey = "jFWvocNDLYMrBzkcTUrmXgenzefPTsFC"; //商户秘钥key 从用户后台获秘钥
$fxgetway = "http://www.jftong5.com/Pay"; //网关


  //
  {
    "fxid"  : "2018128",
    "fxddh" : "no0d5426bff6e1ef69",
    "fxdesc": "pay",
    "fxfee" : "1.00",
    "fxnotifyurl" : "http://dsf.dsvip88.com/callBack/no0d5426bff6e1ef69/JieFuTong",
    "fxbackurl"   : "http://350gtv.com",
    "fxpay"  : "alipay",
    "fxsign" : "1bc84b420aa00d2dd080055c682a8dac",
    "fxip"   : "172.30.5.38"
  }
  //

*/

$fxloaderror = 0; //是否开启数据记�?用于排错 0不开�?1开�?

function getHttpContent($url, $method = 'GET', $postData = array()) {
    $data = '';
    $user_agent = $_SERVER ['HTTP_USER_AGENT'];
    $header = array(
        "User-Agent: $user_agent"
    );
    if (!empty($url)) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); //30秒超�?
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
			if(strstr($url,'https://')){
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			}

            if (strtoupper($method) == 'POST') {
                $curlPost = is_array($postData) ? http_build_query($postData) : $postData;
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            }
            $data = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $data = '';
        }
    }
    return $data;
}

function getClientIP($type = 0, $adv = false) {
    global $ip;
    $type = $type ? 1 : 0;
    if ($ip !== NULL)
        return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array(
        $ip,
        $long) : array(
        '0.0.0.0',
        0);
    return $ip[$type];
}
?>