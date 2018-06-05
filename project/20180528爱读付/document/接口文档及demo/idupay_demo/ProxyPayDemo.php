<?php
/**
 * 发起代付
 * Date: 2018/1/16
 * Time: 11:35
 */


include 'common.php';
include 'HttpClient.class.php';

$request_id =  date("Ymdhis",time())."000002";//订单编号
$ord_amount = "1" ;//支付金额
$proxy_type="T0";
$product_type = "B2CPAY";//产品类型B2CPAY WEIXIN ALIPAY
$account_type = "PRIVATE_DEBIT_ACCOUNT";//PRIVATE_DEBIT_ACCOUNT 对私借记卡  PUBLIC_ACCOUNT 对公
$mobile = "13701111111";
$real_name = "**";
$cert_type = "IDENTITY";//证件类型 IDENTITY 身份证
$cert_no = "34**2938";//代付证件号
$card_no = "622**4436";//银行卡号
$bank_clear_no = "BOCO";//清算行行号
$bank_name = "交通银行";//开户行名称
$bank_code = "BCM";//联行号（简称）
$bank_branch_no = "301364001546";//代付开户银行支行行号
//$bank_branch_name = "中国建设银行股份有限公司xxxx支行";/代付开户行支行名称
//$province = "河北省";
//$city = "沧州市";

$req = array('merchant_no'=>$merchant_no,'trx_key'=>$trx_key,'request_id'=>$request_id,'ord_amount'=>$ord_amount,'proxy_type'=>$proxy_type,
    'product_type'=>$product_type,'account_type'=>$account_type,'mobile'=>$mobile,'real_name'=>$real_name,
    'cert_type'=>$cert_type,'cert_no'=>$cert_no,'card_no'=>$card_no,'bank_clear_no'=>$bank_clear_no,'bank_name'=>$bank_name
,'bank_code'=>$bank_code,'bank_branch_no'=>$bank_branch_no);

$sign = sign($req);
$req["sign"] = $sign;
echo "<br>请求的数据：";
print_r($req);

try {
    $return_message = HttpClient::quickPost($proxy_url, $req);
    echo "<br> 返回的数据：" . $return_message;
}catch(Exception  $e){
    echo 'Message: ' .$e->getMessage();
}

