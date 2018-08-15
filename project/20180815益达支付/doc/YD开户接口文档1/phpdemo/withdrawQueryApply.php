<?php
/**
*代付查询接口测试
*/
ini_set('date.timezone','Asia/Shanghai');
require_once "lib/Pay.Api.php";

$inputCharset=$_POST["inputCharset"];
$partnerId= $_POST["partnerId"];
$orderNo =$_POST['orderNo'];
$signType =$_POST['signType'];
$param=array(  
	"inputCharset"=>$inputCharset,  
    "partnerId"=>$partnerId,
	"orderNo"=>$orderNo,
	"signType"=>$signType
); 
$result=PayApi::withdrawStatus($param);
header("Content-Type: text/html; charset=utf-8");
$errCode = $result["errCode"];
$errMsg = $result["errMsg"];
if($errCode=="0000"){
    echo "查询成功,接收到如下数据:";
	echo "<br>";	
	print_r($result);
}else{
	echo "发生错误！";
	echo "<br>";
	echo "错误代码:".$errCode;
	echo "<br>";
	echo "错误描述:".$errMsg;
}