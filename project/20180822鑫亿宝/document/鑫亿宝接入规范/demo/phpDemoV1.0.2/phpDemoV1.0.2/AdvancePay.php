<?php
/**
 * Created by PhpStorm.
 * User: liuguang
 * Date: 2017/12/21
 * Time: 1:04
 */
include 'HttpClient.class.php';
include 'Utils.php';

header("Content-type: text/html; charset=utf-8");

$url = 'https://47.92.34.10/payGateway/advancePay/v2';
$dateTime = new DateTime;
$date = $dateTime->format('YmdHis');
$md5key = 'sdfsdfsddfasjhyuhjhj';//商户配置密钥
$pay = array();

$pay['channelCode'] = 'rt_ap';
$pay['mchntCode'] = '1033000000100001';//测试商户号
$pay['mchntOrderNo'] = time();

$pay['orderAmount'] = 100;
$pay['accountName'] = 'xxx';
$pay['accountNo'] = '6224444331699914';
$pay['phone'] = '13011114444';
$pay['bankName'] = '光大银行';
$pay['bankCode'] = 'CEB';

$pay['orderSummary'] = '测试物品';
$pay['accountType'] = '1';
$pay['ts'] = $date.'123';
$pay['orderTime'] = $date;

ksort($pay);

$msg = signMsg($pay, $md5key);
echo '[签名前请求明文(组装参数+MD5秘钥)]:'.$msg;
echo "<hr>";
$pay['sign'] = strtoupper(md5($msg));
echo '[签名后结果]:'.$pay['sign'];
print_r($pay);
$respMsg = HttpClient::quickPost($url, json_encode($pay));
echo "返回信息:".$respMsg."\r\n";

$respArray = json_decode($respMsg,true);


?>