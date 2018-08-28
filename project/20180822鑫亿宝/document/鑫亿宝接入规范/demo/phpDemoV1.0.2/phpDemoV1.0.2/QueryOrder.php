<?php
/**
 * Created by PhpStorm.
 * User: liuguang
 * Date: 2017/12/3
 * Time: 11:19
 */

header("content-Type: text/html; charset=UTF-8");
$dateTime = new DateTime;

$date = $dateTime->format('YmdHis');
$md5key = "sdfsdfsddfasjhyuhjhj";//商户配置密钥
$pay = array();
$pay['mchntCode'] = '1033000000100001';//测试商户号
$pay['mchntOrderNo'] = '20180321171256605';
$pay['sysOrderNo'] = 'TT026518037799701083401';
$pay['channelCode'] = 'rt_ap';
$pay['ts'] = $date.'123';

ksort($pay);
$msg = signMsg($pay, $md5key);

echo 'Sign Before MD5[签名前请求明文(组装参数+MD5秘钥)]:'.$msg;
echo "</br></br>";
$pay['sign'] = strtoupper(md5($msg));

echo 'Sign MD5 finaly[签名后结果]:'.$pay['sign'];
echo "</br></br>";
$url = 'https://47.92.34.10/payGateway/order/query/v2';//访问地址
echo 'create order array data[创建订单最终参数]:';
print_r($pay);
echo "</br></br>";
// 引入httpclient类库
include 'HttpClient.class.php';
$respMsg = HttpClient::quickPost($url, json_encode($pay));
echo "[创建订单返回结果]:".$respMsg;
echo "</br></br>";

$respArray = json_decode($respMsg,true);
?>