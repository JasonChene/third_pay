<?php
/**
 * Created by PhpStorm.
 * User: huang
 * Date: 2017/12/29
 * Time: 9:57
 */

//$merchantNo="";

$payKey="";//商户支付key

$paySecret="";//密钥串

$subPayKey="";//子商户paykey，大商户模式必填

$b2cPayUrl="https://gateway.xxx.com/b2cPay/initPay";

$payQueryUrl="https://gateway.xxx.com/query/singleOrder";

$scanPayUrl="https://gateway.xxx.com/scanPay/initPay";

$balanceQueryUr="https://gateway.xxx.com/balance/query";

$toAccountProxyPayUrl="https://gateway.xxx.com/backProxyPay/initPay";

$proxyPayQueryUrl="https://gateway.xxx.com/proxyPayQuery/query";

$notifyUrl="http://www.baidu.com";//后台异步通知地址

$returnUrl="http://www.baidu.com";//页面通知地址

$orderIp="47.91.249.137";



function signString($pieces){
    ksort($pieces);
    global $paySecret;
    $string='';
    foreach ($pieces as $key=>$value){
        if($value !='' && $value!=null){
                $string=$string.$key.'='.$value.'&';
        }
    }
    $string=$string.'paySecret='. $paySecret;
    echo  $string;
    $sign=strtoupper(md5($string));
    $string=$string.'&sign='.$sign;
    return $string;
}



