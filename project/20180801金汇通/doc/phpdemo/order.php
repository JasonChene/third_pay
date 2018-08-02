<?php  

require_once "lib/sing.php";//引用文件

$url='http://localhost/api/payment/queryOrder';//接口地址
$ApiKey="3BAD7FA50B9E75859F5CAB9843EB1DE1";

//参数
$pars=array();
$pars['date']='1523875802853';
$pars['merchantNo']='982103227019296768';
$pars['nonceStr']='131';
$pars['operationCode']='order.query';
$pars['tradeNo']='1523878676196';
$pars['version']='1.0';

$pars['sign']=strtoupper(sign($pars,$ApiKey));

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

//数据转化
$data=json_decode($output);


//判断结果
if($data->code==100){
	//成功事件
	
}else{
	//失败事件
}