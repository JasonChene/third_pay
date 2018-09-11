<?php
/**
 * 商户余额
 * Date: 2017/12/29
 * Time: 13:04
 */

@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$param = array();
$param["merchantNo"] = $merchantNo;
$param["payKey"] = $payKey;// 商户支付Key

$string=signString($param);

echo '<br>';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$balanceQueryUr);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
$data = curl_exec($ch);

echo "返回的数据".$data;