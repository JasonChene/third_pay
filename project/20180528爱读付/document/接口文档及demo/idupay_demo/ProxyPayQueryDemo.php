<?php
/**
 * 代付查询
 * Date: 2018/1/16
 * Time: 12:19
 */


include 'common.php';
include 'HttpClient.class.php';

$request_id =  "20180116022719000002 ";//订单编号

$req = array('trx_key'=>$trx_key,'request_id'=>$request_id);

$sign = sign($req);
$req["sign"] = $sign;
echo "<br>请求的数据：";
print_r($req);
try {
    $return_message = HttpClient::quickPost($proxy_query_url, $req);
    echo "<br> 返回的数据：" . $return_message;
}catch(Exception  $e){
    echo 'Message: ' .$e->getMessage();
}
