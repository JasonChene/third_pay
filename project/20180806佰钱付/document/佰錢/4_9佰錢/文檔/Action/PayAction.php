<?php
require_once '../Config/init.php';

$total_fee = isset($_POST["total_fee"])? trim($_POST["total_fee"]):"0";//订单金额
$PaymentType = isset($_POST["PaymentType"])? trim($_POST["PaymentType"]):"";//支付编码，当为空时代表银行网关间连
$isApp  = isset($_POST["isApp"])? trim($_POST["isApp"]):"";

$DataContentParms =array();
$DataContentParms["X1_Amount"] = $total_fee; //订单金额
$DataContentParms["X2_BillNo"] = date("YmdHis").rand(100,100000);//订单号
$DataContentParms["X3_MerNo"] = $merchant_ID;//商户号
$DataContentParms["X4_ReturnURL"] = $return_url;
$DataContentParms["X6_MD5info"] = Util::GetMd5str($DataContentParms,$key);

$DataContentParms["X5_NotifyURL"] = $notify_url;
$DataContentParms["X7_PaymentType"] = $PaymentType;
$DataContentParms["X8_MerRemark"] = "desc";
$DataContentParms["X10_AccNo"] = $_POST['acc_no'];
$DataContentParms["isApp"] = $isApp; //固定值： 值为"app",表示app接入； 值为空，表示web接入


//var_dump($DataContentParms);die;
if ($DataContentParms["isApp"] == "app"){
	$HtmlStr = HttpClient::Post($DataContentParms, $Pay_url);
}else{
	$HtmlStr = HttpClient::Html($Pay_url, $DataContentParms);
}

echo $HtmlStr;