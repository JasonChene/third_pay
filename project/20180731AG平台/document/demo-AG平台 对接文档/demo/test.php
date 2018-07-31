<?php
/**
 * Created by PhpStorm.
 * User: kkkkkkk
 * Date: 2018/7/3
 * Time: 下午5:36
 */

function url_get_contents ($Url) {
    if (!function_exists('curl_init')){
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1");

    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //get the code of request

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    if($httpCode == 400)
        return 'No donuts for you.';
    if($httpCode == 200) //is ok?
        return $output;
}


/**
 * 发送请求
 * @param $method
 * @param $url
 * @param $vars
 * @return mixed
 * @throws Exception
 */
function httpClient($url)
{
    $http_errors = array
    (
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
    );

    $headers = array(
        "User-Agent: pay/php/1.09",
        'Content-Type: application/json'
    );
    try {
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        }
        $response = curl_exec($ch);
        //$response=mb_convert_encoding($response,  'GB2312','UTF-8');
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (isset($http_errors[$code])) {
            throw new Exception('Response Http Error - ' . $http_errors[$code], $code);
        }
        $code = curl_errno($ch);
        if (0 < $code) {
            throw new Exception('Unable to connect to ' . $url . ' Error: ' . "$code :" . curl_error($ch), $code);
        }
        curl_close($ch);
        return $response;
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}

function getReqUrl($url, $param)
{
    $param = str_replace('?', '', $param);
    parse_str($param, $param);
    $param = http_build_query($param);
    return $url . $param;
}

$url  = "http://zf.bb4pay.com/bb4pay/api/v2/talipayh5/pay?";

$url = getReqUrl($url,"&appid=4989&callbackUrl=https://www.agpay88.com/Pay_BB_callbackurl.html&cancel_url=https://www.agpay88.com/Pay_BB_callbackurl.html&goodName=商品&money=1000&return_url=https://www.agpay88.com/Pay_BB_callbackurl.html&service=alipayh5&transp=20180705134546975148&sign=fffa6d63bac3c2d1dcfa02bae419723d");
echo httpClient($url);
