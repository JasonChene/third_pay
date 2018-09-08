<?php  

require_once "lib/sing.php";//引用文件
require_once "lib/Xcurl.php";//引用文件

$localhost='http://sh.lixinpay.cn:8080';
$url=$localhost.'/api/payment/createBankGatewayOrder';//接口地址

$ApiKey="523850B2CD5944CB9255AD367F28739C";

//参数
$pars=array();
$pars['amount']=10;
$pars['subject']='测试t001--1730136683';
$pars['body']='测试商品357607169';
$pars['paymentType']='BANK_GATEWAY';
$pars['notifyUrl']='http://www.qq.com';
$pars['frontUrl']='http://www.qq.com';
$pars['spbillCreateIp']='127.0.0.1';
$pars['tradeNo']=time();
$pars['operationCode']='order.createOrder';
$pars['version']='1.0';
$pars['date']=time();
$pars['merchantNo']='1029256874471456768';
$pars['bankCode']='01050000'; 
$pars['sign']=sign($pars,$ApiKey);

// $curl=new Xcurl();
// $curl->setHeader(array("content-type"=>"application/x-www-form-urlencoded"));
// $output=$curl->post($url,json_encode($pars));
// print_r($output);
//发起请求
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
//设置header
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $pars);
$output = curl_exec($ch);
curl_close($ch);
print_r($output);
$data=json_decode($output);
print_r($data);
//数据转化
//判断结果
if($data->code==100){
	//跳转url
	// $data->payCode=trim(strrchr($data->payCode, 'http://qr.liantu.com/api.php?&w=280&text='),'http://qr.liantu.com/api.php?&w=280&text=');  
	// header("Location: ".$data->payCode );
}else{
	//失败事件
	//print_r(explode("ERROR.", $data->msg)[1]);
}
