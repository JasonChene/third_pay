<?php
error_reporting(0);
header("Content-Type: text/html; charset=UTF-8");
include_once('config.php');
$data = $_REQUEST['data'];
$data=json_decode(base64_decode($data,true),true);
if($data){
	$appid=$api_config['appid'];
	$appsecret=$api_config['appsecret'];
	$out_trade_no=$data['out_trade_no'];
	$title=$data['title'];
	$money=$data['money'];
	$sign=$data['sign'];
	$trade_no=$data['trade_no'];
	$statu=$data['statu'];
	//签名步骤一：拼接验证签名
	$signarr['appid']=$appid;
	$signarr['out_trade_no']=$out_trade_no;
	$signarr['trade_no']=$trade_no;
	$signarr['money']=$money;
	$string=ToBuff($signarr);
	//签名步骤二：在string后加入KEY
	$string = $string . "&key=".$appsecret;
	//签名步骤三：MD5加密
	$string = md5($string);
	//签名步骤四：所有字符转为大写
	$signstring = strtoupper($string);

	if($signstring==$sign && $statu=="success"){
		//下面填写您的业务逻辑///////////////////////////////////////////////////////

		//上面填写您的业务逻辑//////////////////////////////////////////////////////
		echo "success";	
	}else{
		//此处为返回值，不可删除，服务器推送订单后如果没有返回该success，则将继续推送3次，返回该字符后，停止推送
		echo "fail";
	}
}else{
	echo 'fail';
}
function ToBuff($urlObj)
        {
            $buff = "";
            foreach ($urlObj as $k => $v)
            {
                if($k != "sign"){
                    $buff .= $k . "=" . $v . "&";
                }
            }
             
            $buff = trim($buff, "&");
            return $buff;
 }