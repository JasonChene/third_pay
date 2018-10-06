<?php
header("Content-type:text/html;charset=utf8");
include('config.php');
include('util.php');
$data["orderNum"]="2018080517240463136";//订单号
$data["organizationCode"]=$merNo;//商户号
$data["payDate"]="2018-08-05";//时间

//生成签名
$sign=createSign($data,$signKey);
//生成 json字符串
$json = jsonEncode($data);
//加密
$dataStr =encodePay($json,$public_key);
$reqParam["data"]=$dataStr;
$reqParam["merNo"]=$merNo;
$reqParam["sign"]=$sign;

//提交
$result=reqPost($queryUrl,$reqParam);

$resultArray=json_decode($result,true);

if($resultArray["status"]=="200"){	
	//解密
	$resultJson=decode($resultArray['data'],$private_key);		
	$resultData=jsonToQuery($resultJson,$signKey,$resultArray["sign"]);
	if($resultData['payStateCode']=="00"){
		echo "未支付";
	}else if($resultData['payStateCode']=="10"){
		echo "支付成功";
	}else if($resultData['payStateCode']=="20"){
		echo "支付失败";
	}else if($resultData['payStateCode']=="30"){
		echo "支付中";
	}else{
		echo $resultData['payStateCode'].",错误信息：".$resultData['$resultData'];
	}
}else{
	echo $resultArray["message"];
}



