<?php
/**
*支持接口测试
*/
ini_set('date.timezone','Asia/Shanghai');
require_once "lib/Pay.Api.php";
$inputCharset=$_POST["inputCharset"];
$partnerId= $_POST["partnerId"];
$returnUrl=$_POST["returnUrl"];
$notifyUrl =$_POST['notifyUrl'];
$orderNo =$_POST['orderNo'];
$orderAmount =$_POST['orderAmount'];
$orderCurrency =$_POST['orderCurrency'];
$orderDatetime=$_POST["orderDatetime"];
$subject= $_POST["subject"];
$body=$_POST["body"];
$signType =$_POST['signType'];
$extraCommonParam =$_POST['extraCommonParam'];
$payMode =$_POST['payMode'];
$bnkCd = $_POST['bnkCd'];
$cardNo = $_POST['cardNo'];
$accTyp = $_POST['accTyp'];
$param=array(  
	"inputCharset"=>$inputCharset,  
    "partnerId"=>$partnerId,
	"returnUrl"=>$returnUrl,  
    "notifyUrl"=>$notifyUrl,
	"orderNo"=>$orderNo,
    "orderAmount"=>$orderAmount,  
    "orderCurrency"=>$orderCurrency,
    "orderDatetime"=>$orderDatetime,
	"subject"=>$subject,
	"body"=>$body,
	"signType"=>$signType,
	"extraCommonParam"=>$extraCommonParam,
	"payMode"=>$payMode,
	"bnkCd"=>$bnkCd,
	"cardNo"=>$cardNo,
	"accTyp"=>$accTyp
); 
$result=PayApi::unifiedOrder($param);
header("Content-Type: text/html; charset=utf-8");
$errCode = $result["errCode"];
$errMsg = $result["errMsg"];
if($errCode=="0000"){ 
	$qrCode = $result['qrCode'];
	$retHtml = $result['retHtml'];
	if(empty($qrCode)){//非扫码支付返回html
		echo $retHtml;
	}else{//扫码支付生成二维码
		include "tools/phpqrcode.php";
		$errorCorrectionLevel = "L";
		$matrixPointSize = "8";
		$margin="1";
		QRcode::png($qrCode, false, $errorCorrectionLevel, $matrixPointSize,$margin);
	}
}else{
	echo "支付失败，发生错误！";
	echo "<br>";
	echo "错误代码:".$errCode;
	echo "<br>";
	echo "错误描述:".$errMsg;
}
