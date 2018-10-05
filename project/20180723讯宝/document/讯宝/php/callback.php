<?php 
die('ok');
$orderid=$_REQUEST["orderid"];//商户订单号

$opstate=$_REQUEST["opstate"];//订单结果

$ovalue=$_REQUEST["ovalue"]; //订单金额

$sysorderid=$_REQUEST["sysorderid"];//讯宝商务订单号

$systime=$_REQUEST["systime"];//讯宝商务订单时间

$sign=$_REQUEST["sign"];//MD5签名

$m_id=$_REQUEST["attach"];//备注信息



$preEncodeStr="orderid=".$orderid.
"&opstate=".$opstate.
"&ovalue=".$ovalue.
"&time=".$systime.
"&sysorderid=".$sysorderid.$tokenKey;


$record_file=fopen("./recrd.txt", 'a');
fwrite($record_file, $preEncodeStr."\n");
fclose($record_file);



$encodeStr=md5($preEncodeStr);



if($sign==$encodeStr){//签名成功

	if($opstate=="0"){//如果订单结果为支付成功

		echo "opstate=0";

		$record_file=fopen("./recrd.txt", 'a');
		fwrite($record_file, "\n opstate=0");
		fclose($record_file);

	}


}else{	//签名失败

	echo "opstate=-2";
}