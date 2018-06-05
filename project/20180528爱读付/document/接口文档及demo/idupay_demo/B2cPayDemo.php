<?php
/**
 * 网关支付Demo
 * Date: 2017/12/29
 * Time: 12:33
 */

include 'common.php';
include 'HttpClient.class.php';

$ord_amount = "1" ;//支付金额
$request_id =  date("Ymdhis",time())."000001";//订单编号
$product_type = "50102";//50102:T0;50103:T1  产品类型
$request_time = date("Ymdhis",time());
$goods_name = "背包";
$request_ip = "127.0.0.1";
$bank_code = "BOCO";
$account_type = "PRIVATE_DEBIT_ACCOUNT";
$remark = "支付备注";

$req = array('trx_key'=>$trx_key,'ord_amount'=>$ord_amount,'request_id'=>$request_id,'product_type'=>$product_type,'request_time'=>$request_time,'goods_name'=>$goods_name,'request_ip'=>$request_ip,'bank_code'=>$bank_code,'account_type'=>$account_type,'remark'=>$remark,'return_url'=>$return_url,'callback_url'=>$callback_url);

$sign = sign($req);
$req["sign"] = $sign;
echo "<br>请求的数据：";
print_r($req);
try {
    $return_message = HttpClient::quickPost($b2c_url, $req);
    echo "<br> 返回的数据：" . $return_message;
}catch(Exception  $e){
     echo 'Message: ' .$e->getMessage();
 }






