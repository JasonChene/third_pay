<?php
/**
 * 支付订单查询
 * Date: 2017/12/29
 * Time: 11:57
 */
@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$param = array();
$param["payKey"] = $payKey;// 商户支付Key
$param["outTradeNo"] = "171229031454000001";//原交易订单号

$string=signString($param);

echo '<br>';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$proxyPayQueryUrl);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
$data = curl_exec($ch);

echo  "<br> 返回的数据：".$data;
echo  "<br> ";

$returnData=json_decode($data,false);
//响应码 0000：打款成功；9996：打款中
if("0000" ==  $returnData->resultCode){
    echo '请求成功<br> ';
    echo $returnData->outTradeNo;//商户出款订单号
    echo $returnData->remitStatus;//REMIT_SUCCESS:结算成功；REMIT_FALL:结算失败；RIMITTING:结算中
    echo $returnData->settAmount;//结算金额，单位：元，2位小数点
    echo $returnData->completeDate; //结算完成时间
    echo $returnData->sign;
    

}else{
    echo $returnData->errMsg;//响应码不为"0000"的时候非空
}


curl_close($ch);
