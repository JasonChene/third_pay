<?php

define('SRC_CODE', '');
define('SRC_KEY', '');
require_once('function.php');

$mchid = get_mchid();
$api_url = '';

//下单信息
$order_info = array();
$order_info['src_code'] = SRC_CODE;
$order_info['mchid'] = $mchid;
$order_info['out_trade_no'] = 123;
$order_info['total_fee'] = 32;
$order_info['time_start'] = date('YmdHis');
$order_info['goods_name'] = '火腿肠';
$order_info['trade_type'] = 40104;
$order_info['finish_url'] = 'http://www.baidu.com';

//把md5校验值加入参数数组
$order_info['sign'] = get_md5($order_info, SRC_KEY);
//调用接口
$res = http($api_url, $order_info);
if($res['http_code'] == 200){
	$return_info = json_decode($res['http_data']);
	echo '得到接口返回值如下'."\n";
	print_r($return_info);
}
else{
	echo '接口错误，记录等操作';
}
