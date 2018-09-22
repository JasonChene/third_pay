<?php

$post = file_get_contents("php://input");
//$post = '{"user_account":"Ameizing","out_trade_no":"12345678","payment_type":"wxpay","trade_no":"1811880671","total_fee":"0.02","notify_time":"2018-04-30 04:49:54","body":"xxxxx","status":"SUCCESS","sign":"7BD9A7062AC19467A0BD5822B3FC0F5B"}';

$data = json_decode($post, true);

$key = '';			//密钥


$myfile = fopen("notify.txt", "a") or die("Unable to open file!");

fwrite($myfile, json_encode($data).'\n');
fclose($myfile);

if(_validate_sign($data, $key))
{
	 //业务逻辑处理

	 if($data['status'] == 'SUCCESS')
	 {
		//支付成功
	 }
	 else
	 {
		//支付失败
	 }

	echo 'SUCCESS';
}
else
{
	echo 'FAIL';
}

function _validate_sign($data, $key)
{

    $sign = $data['sign'];
    unset($data['sign']);
    	
    //签名步骤一：按字典序排序参数
    ksort($data);
	//签名步骤二：使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串
    $string = _to_url_params($data);
    //签名步骤三：在string后加入KEY
    $string = $string . "&key=".$key;
    //签名步骤四：MD5加密
    $string = md5($string);
    //签名步骤五：所有字符转为大写
    $result = strtoupper($string);
    	
    if($result == $sign)
    {
    	return true;
    }
    else 
    {
    	return false;
    }
}

function _to_url_params($data)
{
	$buff = "";
    foreach ($data as $k => $v)
    {
    	if($k != "sign" && $v != "" && !is_array($v)){
    		$buff .= $k . "=" . $v . "&";
    	}
    }
    
    $buff = trim($buff, "&");
    return $buff;
}



?>