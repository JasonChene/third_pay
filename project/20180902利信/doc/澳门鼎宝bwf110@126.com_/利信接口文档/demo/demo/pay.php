<?php  

require_once "lib/sing.php";//引用文件
require_once "lib/Xcurl.php";//引用文件

$localhost='http://sh.lixinpay.cn:8080';
$url=$localhost.'/api/payment/createOrder';//接口地址

$ApiKey="55A3756A7A364AD7A06772A1E690264F";

//参数
$pars=array();
$pars['amount']=100;
$pars['subject']='测试t001--1730136683';
$pars['body']='测试商品357607169';
$pars['paymentType']='WEIXIN_QRCODE';
$pars['notifyUrl']='http://www.qq.com';
$pars['frontUrl']='http://www.qq.com';
$pars['spbillCreateIp']='127.0.0.1';
$pars['tradeNo']=time();
$pars['operationCode']='order.createOrder';
$pars['version']='1.0';
$pars['date']='1524893933368';
$pars['merchantNo']='1027126434532950016';

$pars['sign']=sign($pars,$ApiKey);

// $curl=new Xcurl();
// $curl->setHeader(array("content-type"=>"application/json"));
// $output=$curl->post($url,json_encode($pars));
//发起请求
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $pars);
$output = curl_exec($ch);
curl_close($ch);

$data=json_decode($output);
print_r($data);
//数据转化
//判断结果
if($data->code==100){
	//跳转url
	header("Location: ".$data->payCode );
}else{
	//失败事件
	print_r(explode("ERROR.", $data->msg)[1]);
}
