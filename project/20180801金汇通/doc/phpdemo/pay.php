<?php  

require_once "lib/sing.php";//引用文件

$url='http://www.865805.com/api/payment/createBankGatewayOrder';//接口地址

$ApiKey="3BAD7FA50B9E75859F5CAB9843EB1DE1";
//参数
$pars=array();
$pars['amount']=10000;
$pars['bankCode']='01020000';
$pars['subject']='测试t001--1730136683';
$pars['body']='测试商品357607169';
$pars['paymentType']='KUAIJIE';//
$pars['notifyUrl']='http://www.qq.com';
$pars['frontUrl']='http://www.qq.com';
$pars['spbillCreateIp']='127.0.0.1';
$pars['tradeNo']='1524893933368';
$pars['operationCode']='order.createOrder';
$pars['version']='1.0';
$pars['date']='1524893933368';
$pars['sign']=strtoupper(sign($pars,$ApiKey));
$pars=argSorts($pars);
//echo $pars;
$postdata = http_build_query($pars);
	$options = array( 'http' => array( 'method' => 'POST','header' =>'Content-type:application/x-www-form-urlencoded','content' => $postdata,'timeout' =>  60 // 超时时间（单位:s）    
	)  );
	$context = stream_context_create($options);
	$output = file_get_contents($qingqiuURl, false, $context);
$data=json_decode($output);
if($data->code==100){
	//跳转url
	header("Location: ".$data->payCode );
}else{
	print_r($data);
	//失败事件
}
?>