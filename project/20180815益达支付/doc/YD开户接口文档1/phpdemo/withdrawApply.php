<?php
/**
*代付接口测试
*/
ini_set('date.timezone','Asia/Shanghai');
require_once "lib/Pay.Api.php";
$inputCharset=$_POST["inputCharset"];
$partnerId= $_POST["partnerId"];
$notifyUrl =$_POST['notifyUrl'];
$orderNo =$_POST['orderNo'];
$orderAmount =$_POST['orderAmount'];
$orderCurrency =$_POST['orderCurrency'];
$cashType = $_POST['cashType']; //提现类型
$accountName = $_POST['accountName']; //姓名
$bankName = $_POST['bankName']; //银行名称
$bankCardNo = $_POST['bankCardNo']; //银行卡号
$canps = $_POST['canps']; //联行号
$idCard = $_POST['idCard']; //身份证号
$extraCommonParam = $_POST['extraCommonParam']; //公用回传参数
$param=array(  
	"inputCharset"=>$inputCharset,  
    "partnerId"=>$partnerId,
    "notifyUrl"=>$notifyUrl,
	"orderNo"=>$orderNo,
    "orderAmount"=>$orderAmount,  
    "orderCurrency"=>$orderCurrency,
    "cashType"=>$cashType,
	"accountName"=>$accountName,
	"bankName"=>$bankName,
	"bankCardNo"=>$bankCardNo,
	"extraCommonParam"=>$extraCommonParam,
	"canps"=>$canps,
	"idCard"=>$idCard
); 
$result = PayApi :: withdraw($param);
$retCode = $result['errCode'];
$retMsg = $result['errMsg'];
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>代付demo</title>
</head>
<body>
	<div style="text-align: center;color:#556B2F;font-size:30px;font-weight: bolder;">代付结果</div><br/>
	<div align="center">
	   <h3>提交的订单号：<?php echo $orderNo ?> </h3>
	   <p><?php echo $retCode ?></p>
	   <p><?php echo $retMsg ?></p>
	</div>
</body>
</html>