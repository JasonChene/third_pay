<?php
/*
*同步回调跳转处理接口
*这里是用户支付完毕，浏览器跳回你网站的	
*/
require_once 'config.php';

//初始化Config类
$config = new Config;

//获取参数
$params = $_POST;

//记录日志
//$config->inslog($param);

//验签
if($config->requestSignVerify($params)){
    //验签成功
   	//自己的业务处理逻辑，注意务必判断回调金额是否与您数据库中的订单金额一致。
	//建议对每一个回调参数都做好安全过滤和判断。
	//最后

	echo 'success';
}else{
	//验签失败
	echo 'false';
}
