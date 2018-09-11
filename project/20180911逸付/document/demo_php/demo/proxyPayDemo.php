<?php
/**
 *  代付demo
 * Date: 2017/12/29
 * Time: 10:05
 */
@header('Content-type: text/html;charset=UTF-8');
include '../config/payConfig.php';

$param = array();
$param["payKey"] = $payKey;// 商户支付Key
$param["outTradeNo"] = date("ymdhis",time())."000001";//商户请求号
$param["orderPrice"] = "10";//订单金额，保留2位小数
$param["proxyType"] = "T0";//交易类型 T0 或 T1
$param["productType"] = "B2CPAY";//产品类型 B2CPAY  WEIXIN  ALIPAY
$param["bankAccountType"] = "PRIVATE_DEBIT_ACCOUNT";//PRIVATE_DEBIT_ACCOUNT 对私借记卡  PUBLIC_ACCOUNT 对公
$param["phoneNo"] = "1370111111"; //代付银行手机号
$param["receiverName"] = "testname";//账户名 收款人账户名
$param["certType"] = "IDENTITY";//证件类型 IDENTITY 身份证
$param["certNo"] = "500456456456465412";//代付证件号码
$param["receiverAccountNo"] = "621456456456456645813";//银行账号
$param["bankClearNo"] = "ABC";//清算行行号
$param["bankName"] = "农业银行";//开户行名称
$param["bankCode"] = "ABC"; //联行号（简称）
$param["bankBranchNo"] = "301364001546";//代付开户银行支行行号
//$param["bankBranchName"] = "中国建设银行股份西三旗";//代付开户行支行名称
//$param["province"] = "河北省";//代付开户行省份
//$param["city"] = "沧州市";//代付开户行城市

$string=signString($param);

echo '<br>';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$toAccountProxyPayUrl);
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

if("0000" ==  $returnData->resultCode){
    echo '请求成功';
}else{
    echo $returnData->errMsg;
}


curl_close($ch);




