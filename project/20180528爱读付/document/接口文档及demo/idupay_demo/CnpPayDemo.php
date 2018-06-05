<?php
/**
 * 无卡支付demo
 * Date: 2018/1/16
 * Time: 11:32
 */

include 'common.php';
include 'HttpClient.class.php';

$ord_amount = "1" ;//支付金额
$request_id =  date("Ymdhis",time())."000002";//订单编号
$product_type = "70203";
$request_time = date("Ymdhis",time());
$goods_name = "背包";
$request_ip = "127.0.0.1";//ip字段不明确
$remark = "支付备注";

$req = array('trx_key'=>$trx_key,'ord_amount'=>$ord_amount,'request_id'=>$request_id,'product_type'=>$product_type,'request_time'=>$request_time,'goods_name'=>$goods_name,'request_ip'=>$request_ip,'remark'=>$remark,'return_url'=>$return_url,'callback_url'=>$callback_url);

$sign = sign($req);
$req["sign"] = $sign;
echo "<br>请求的数据：";
print_r($req);

try {
    $return_message = HttpClient::quickPost($cnp_url, $req);
    echo "<br> 返回的数据：" . $return_message;
}catch(Exception  $e){
    echo 'Message: ' .$e->getMessage();
}

