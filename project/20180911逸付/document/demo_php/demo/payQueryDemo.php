<?php
/**
 * 支付查询
 * Created by PhpStorm.
 * Date: 2017/12/29
 * Time: 13:15
 */
@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$param = array();
$param["payKey"] = $payKey;// 商户支付Key
$param["outTradeNo"] = "20171229031336000002";//商户支付请求号

$string=signString($param);

echo '<br>';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$payQueryUrl);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
$data = curl_exec($ch);

echo  "<br> 返回的数据：".$data;
curl_close($ch);