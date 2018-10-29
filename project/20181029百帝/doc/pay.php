<?php

/**
 * 余额代付
 * 接口请求地址：http://{{domain}}/api-v1-full/full
 */

$privateKey = "-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAKYEmNpjI0L8gV/h
4mbyIKOY4f03sBU0EZBos4QoZ4RA7oHyx9dLemDN8l+0pV3QZh8ipN/WBfpnXCdV
z1kiiCTHQJT5LkfJXNbrMx8QivYE3wtD1LiHoB7YkdqYb5yfGa/DGMaCvimjA+78
8Ogt7A80yX/UpZ9caz8Ss1CUIRitAgMBAAECgYEAoppsD7H77nccTAoU0pmiCDoM
VhP9/baC4Xr7IJzmTq2+LT7aJu+BTGFKXBy1vv4Hl8U50RZxwoELzGcKcBYXWsID
1LE57bQgbv+sTnPIyUFmun1RhzqA8K5PU5YuwZ8Pf6jqiN8DO5LlFMcLIY/kJufE
R0btp9XRe7oWFxl6i4ECQQDY9RJ4hTZzl091actr6+aJ2BRzaY4qCGaTg/KzqWoz
8gIWn7eZwdUb7iS5bbTMOEdkX6FUZfVjrMxorJ3b7zwhAkEAw+TRdg+tTmSRJaZd
j7fVzFTHXLE6eEwP/2dq/Ac1sZIQu0W8EpIIo6d1DQWTx+fU5VO58rX/2yuFBg8h
6bGrDQJBAL6VOe6JBrYvuusnTjy1c0SvffeMSAgAbSs0g6TzM4oCE3eQQhZdQTlR
zwzcpC+pWH2BzBR5pEA08TMaP2mOFEECQAPvAFXCktRUKKX85TwRkPV9blNqK6Zm
wJt8VCWjb2yVZkicad5lmE7Q+gS86+7DtP6147H//ZdFFHK+swuiSbECQBRLypvq
lTTxxuO1l/EBPw9F9Jm/CUOvridGZ6+pvWkrhD04pgp4sbwbw7zPueKwwxCzSO1P
I0YkMwJD37YcgYM=
-----END PRIVATE KEY-----";

$sysPubKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDD4eufco4/RRACZ1kREFymyadT
KtzT9B6FZDSKJQ+Z4VE/pCcNklESip/v5KrmWupbqd6jan8gudLuAVduBpUIliaq
Tjnm0yW5VOvmzRcSkFsiR2L9Q2PgiI0dsSgvF4LTasRk2EKvA6YksPQ5u8sYdAsq
4upOGd070d1H2B7OqwIDAQAB
-----END PUBLIC KEY-----";

$desKey = "d010214420aab061a17aced7";
//$desKey = "31de0c7c5034bc1e4136d60c";

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

function Update($data){
    //ksort($data);
    $temp = [];
    foreach($data as $i => $v){
        if(isset($v)){
            $temp[] = $i . "=" . $v;
        }
    }
    return join("&", $temp);
}

function curl_post($url, $data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);//设置连接等待时间30秒
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    //echo '耗时'.$info['total_time'].'秒'."\n";
    //echo 'http状态'.$info['http_code']."\n";
    if (curl_errno($ch)) {
        print curl_error($ch);
    }
    curl_close($ch);
    return json_decode($output, true);
}

$arr = array(
    'mchid'=>'00010001',
    'submchid' => '00010001000000000001',
    'orderno'=>'',
    'waytype'=>'handpay',
    'amount'=>'1.18',
    'cardno'=>'6222222222222222',
    'payname'=>'王地球',
    'bankno'=>'122222222222',
    'bankname'=>'中信银行',
    'notifyurl' => 'http://a.b.c'
);

//生成订单号
$arr['orderno'] = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

$arr['cardno'] = base64_encode(openssl_encrypt($arr['cardno'], "des-ede3-cbc", $desKey, true, "01234567"));
$arr['payname'] = base64_encode(openssl_encrypt($arr['payname'], "des-ede3-cbc", $desKey, true, "01234567"));
//print_r("cardno=".$arr['cardno']."\n");
$str = SortToString($arr);
//print_r("签名原数据=".$str."\n");
openssl_sign($str, $sign, openssl_pkey_get_private($privateKey), OPENSSL_ALGO_SHA1);
$signData = base64_encode($sign);//最终的签名
//print_r("签名结果=".$signData."\n");

$arr['cardno'] = urlencode($arr['cardno']);
$arr['payname'] = urlencode($arr['payname']);
$str = Update($arr);

$str = $str."&sign=".urlencode($signData);
print_r("http请求数据：\n".$str."\n");
$rsp = curl_post('http://pay.zbc555.cn/api-v1-full/full', $str);
print_r("http响应数据：\n");
print_r($rsp);
$code = $rsp['code'];
//保留sign
$rspSign = $rsp['sign'];
unset($rsp['sign']);
if($code == "1")
{
    $rspData = $rsp['data'];
    //data排序
    $rspData = SortToString($rspData);
    $rsp['data'] = $rspData;
    
    //所有的排序
    $rsp = SortToString($rsp);
    
    $verify = openssl_verify($rsp, base64_decode($rspSign), openssl_pkey_get_public($sysPubKey));
    if($verify == TRUE) {
        print_r("交易成功，签名验证成功！\n");
    }
    else {
        print_r("交易成功，签名验证失败！\n");
    }
}
else
{
    print_r("交易失败[".$rsp['msg']."]\n");
}