<?php

// 代付
$data = array(
'cmd'=> 'DPAYTOTAL',
'version'=> '2.0',
'appid'=>$conf['mch_id'],
'userid'=> $conf['userid'],
'apporderid' => $orderInfo['order_sn'],
'ordertime'=> date('YmdHis'),
'orderbody'=> 'desc',
'amount'  => $orderInfo['money'],
'bankno' => 'XXXX',
'banksettno' => 'XXXX',
'acctno' => des($orderInfo['bank_cardno'],$conf['desKey']),
'acctname' => des($orderInfo['bank_account'],$conf['desKey']),
'bankname' => $bankList[$bankCode],
'mobile' => des('13696987485',$conf['desKey']),
'bankcode' => $banks[$bankCode],
'certificatecode' => des('XXXX',$conf['desKey']),
'province' => '广东',
'city' => '佛山',
'notifyurl'    => $conf['notifyurl'],
);


$data = array_filter($data);
ksort($data);
$signature = sprintf('%s%s', urldecode(http_build_query($data)), $conf['key']);

$this->log("apply_signStr1: " . $signature);
$data['hmac'] = md5($signature);

// curl post请求
$re = curl_page($gateway, true, $data, 60);


function des($data,$deckey){

    $str = openssl_encrypt($data,'des-ecb',$deckey);
    $str =  base64_decode($str);
    $str = bin2hex($str);
    return $str;
}

function curl_page($url, $post=0, $postdata='', $connect_timeout=3, $timeout=3,$header=array()) {

    $a = microtime(true);

    if (is_array($postdata)) $postdata = _make_curl_posts($postdata);

    $user_agent ="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";

    $ch = curl_init($url);
    //curl_setopt ($ch, CURLOPT_HEADER, 0);
    //    	curl_setopt ($ch, CURLOPT_COOKIE , $this->cookie);
    if(!empty($header)){
        //curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    }else{
        curl_setopt ($ch, CURLOPT_HEADER, 0);
    }

    // 允许重定向
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    // 支持HTTPS(SSL)
    if (preg_match('/^https/', $url)) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
    }

    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
    curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt ($ch, CURLOPT_REFERER, $url);

    // 是否启用POST提交
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    }

    $ret = curl_exec($ch);

    $header = curl_getinfo($ch);

    // 更新http_code
    $http_code = isset($header['http_code']) ? $header['http_code'] : 200;

    $error = '';
    if ($http_code != 200) {
        $error = curl_error($ch);
    }

    curl_close ($ch);

    $b = microtime(true);

    $exec_time = $b-$a;

    /// 如果执行时间大于1秒，记录DNS
    if ($exec_time > 1.0) {

        $header_str = 'total_time:'.$header['total_time'].',namelookup_time:'.$header['namelookup_time'].',connect_time'.$header['connect_time'].',pretransfter_time:'.$header['pretransfer_time'];
        //	    api_log($header_str);
    }

    $r = array(
        'code' => $http_code,
        'result' => $ret,
    );

    return $r;
}




// 支付

$data = [
    'cmd'        => $cmd,
    'version'    => $version,
    'hmac'       => $hmac,
    'appid'      => $appid,
    'ordertime'  => $ordertime,
    'userid'     => $userid,
    'apporderid' => $apporderid,
    'orderbody'  => $orderbody,
    'orderdesc'  => $orderdesc,
    'amount'     => $amount,
    'notifyurl'  => $notifyurl,
    'custip'     => $custip,
];

$data = array_filter($data);
ksort($data);
$signature = sprintf('%s%s', urldecode(http_build_query($data)), $key);
$data['hmac'] = md5($signature);

// curl post请求
$re = curl_page($gateway, true, $data, 60);
parse_str($re, $query);
if (!empty($query) && $query['payurl'] != 'null') {
    $decrypted = openssl_decrypt(base64_encode(hex2bin($query['payurl'])), 'des-ecb', $desKey);
    redirect($decrypted);
}