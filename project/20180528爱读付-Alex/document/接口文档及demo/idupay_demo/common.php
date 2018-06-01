<?php
/**
 * 公共
 * Date: 2017/12/29
 * Time: 9:57
 */
$merchant_no="PAY88882018031610001114";//商户编号

$trx_key="53e70cb3b9c84a25a577530ed76b4909";//商户支付key

$secret_key="8dbcd9240bb44ffb83ef14888015a551";//密钥串

$b2c_url="http://gateway.idupay.cn/b2c/gateway";

$cnp_url="http://gateway.idupay.cn/cnp/gateway";

$order_query_url="http://gateway.idupay.cn/order/query";

$merchant_balance_url = "http://gateway.idupay.cn/merchant/balance";

$proxy_url="http://gateway.idupay.cn/proxy/gateway";

$proxy_query_url="http://gateway.idupay.cn/proxy/query";

$callback_url = "http://localhost:8080/demo/callback/notify";

$return_url="http://www.baidu.com";//页面通知地址

function sign($pieces){
    ksort($pieces);
    global $secret_key;
    $string='';
    foreach ($pieces as $key=>$value){
        if($value !='' && $value!=null){
            $string=$string.$key.'='.$value.'&';
        }
    }
    $string=$string.'secret_key='. $secret_key;
    echo  "待签名字符串：".$string;
    echo  "<br>";
    $sign=strtoupper(md5($string));
    return $sign;
}


