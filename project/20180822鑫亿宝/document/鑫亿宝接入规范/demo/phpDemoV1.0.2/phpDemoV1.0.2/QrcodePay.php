<?php
/**
 * Created by PhpStorm.
 * User: liuguang
 * Date: 2017/12/15
 * Time: 16:47
 */
include 'HttpClient.class.php';
include 'Utils.php';
header("Content-type: text/html; charset=utf-8");

$url = 'https://47.92.34.10/payGateway/payment/qrCode/v2';
$dateTime = new DateTime;
$date = $dateTime->format('YmdHis');
$md5key = "sdfsdfsddfasjhyuhjhj";

$pay = array();
$pay['mchntOrderNo'] = time();

$pay['orderAmount'] = 100;
$pay['clientIp'] = '192.152.111.10';
$pay['subject'] = '测试物品';
$pay['body'] = '惠民手机支付';
$pay['notifyUrl'] = '"http://localhost:8080/payCenter-api/pc/Pay/Notify"';
$pay['pageUrl'] = 'http://baidu.com';
$pay['orderTime'] = $date;
$pay['orderExpireTime'] = $date;
$pay['description']='描述信息';

$pay['channelCode'] = 'qq_wallet_qr'; // qq钱包扫码
$pay['mchntCode'] = '1033000000100001';//测试商户号
$pay['ts'] = $date.'123';

ksort($pay);
$msg = signMsg($pay, $md5key);

echo '[签名前请求明文(组装参数+MD5秘钥)]:'.$msg;
echo "</br></br>";
$pay['sign'] = strtoupper(md5($msg));
echo '[签名]:'.$pay['sign'];
echo "</br></br>";
echo '[创建订单最终参数]:';
print_r($pay);
echo "</br></br>";

$respMsg = HttpClient::quickPost($url, json_encode($pay));

if ("" != $respMsg) {
    echo "[创建订单返回结果]:".$respMsg;
    echo "</br></br>";

    $respArray = json_decode($respMsg,true);
}
?>