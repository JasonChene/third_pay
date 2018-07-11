 <?php 

function pay()
{
		//$type = 909;
	$param = array(
		'amount' => '1000',			           //金额分为单位,这个表示10元
		'mch_id' => '10001',                    //商户号 换成我司分配的商户号
		'notify_url' => 'http://www.baidu.com', //填回调异步通知地址
		'out_trade_no' => "DD" . time(),		   //订单号必须唯一
		'mch_create_ip' => '127.0.0.1',		   //IP地址
		'time_start' => date("YmdHms"),    	   //IP日期
		'body' => '00',                         //默认00 如果是网银支付 则需要我司提供对应银行编码 
		'attach' => 'a10000',                    //默认00
		'nonce_str' => '123456',                 //默认
		'trade_type' => 'F16',                    //Z15表示微信,F16表示快捷 W16表示网银
		'paytype' => 'kj',                      //与trade_type对应,快捷传kj,(支付宝wap或者微信h5)传入wap,网银传wy
		'back_url' => 'http://www.baidu.com',    //默认
	);
	$param['sign'] = makeSignature($param, '10001abc');//10001abc 这个是密钥 需要替换我司分配的对应的密钥
	$url_param = arrayToKeyValueString($param);
		// wap 方式提交
		//if($type == 901 || $type == 904 || $type == 906) {
        //	echo "<script language=\"javascript\">";
		//	echo "location.href=\"http://192.168.43.61:1888/bpay/ytpay/order?$url_param\"";
		//	echo "</script>";
        //} else {
       	// code 方式提交
	$respdata = file_get_contents('http://www.yazang.top/api/quickkj/pay?' . $url_param);
			//$respdata = file_get_contents('http://127.0.0.1:1888/bpay/api/quickkj/pay?'.$url_param);
	echo $respdata;
        //}
}

	/*
 * 生成签名，$args为请求参数，$key为私钥
 */
function makeSignature($args, $key)
{
	if (isset($args['sign'])) {
		$oldSign = $args['sign'];
		unset($args['sign']);
	} else {
		$oldSign = '';
	}

	ksort($args);
	$requestString = '';
	foreach ($args as $k => $v) {
		$requestString .= $k . '=' . ($v);
		$requestString .= '&';
	}
	$requestString = substr($requestString, 0, strlen($requestString) - 1);
	$newSign = md5($requestString . "&key=" . $key);
	return $newSign;
}
	
	/*
 * 签名转换
 */
function arrayToKeyValueString($param)
{
	$str = '';
	foreach ($param as $key => $value) {
		$str = $str . $key . '=' . $value . '&';
	}
	return $str;
}

pay(); // 下单
?>
