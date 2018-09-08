<?php  

require_once "lib/sing.php";//引用文件
require_once "lib/Xcurl.php";//引用文件

$localhost='http://sh.lixinpay.cn:8080';
$url=$localhost.'/api/payment/queryOrder';//接口地址

$ApiKey="55A3756A7A364AD7A06772A1E690264F";

//参数
$pars=array();
$pars['date']='1523875802853';
$pars['merchantNo']='982103227019296768';
$pars['nonceStr']='131';
$pars['operationCode']='order.query';
$pars['tradeNo']='1523878676196';
$pars['version']='1.0';

$pars['sign']=sign($pars,$ApiKey);


$curl=new Xcurl();
$curl->setHeader(array("content-type"=>"application/json"));
$output=$curl->post($url,json_encode($pars));


// //发起请求
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// // post数据
// curl_setopt($ch, CURLOPT_POST, 1);
// // post的变量
// curl_setopt($ch, CURLOPT_POSTFIELDS, $pars);
// $output = curl_exec($ch);
// curl_close($ch);

//数据转化
$data=json_decode($output);


//判断结果
if($data->code==100){
	//成功事件
	
}else{
	//失败事件
}