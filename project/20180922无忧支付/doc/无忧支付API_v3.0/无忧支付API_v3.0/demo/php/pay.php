<?php
	header("Content-type: text/html; charset=utf-8"); 

	$total_fee = $_GET['total_fee'];
	$user_name = $_GET['user_name'];
	$payment_type = $_GET['payment_type'];

	$url = 'https://www.wuyoupay.cn/pay.php';
	$user_account = '';		//商户在无忧的账号
	$key = '';				//密钥

	$params = array(
		'notify_url'	=> '',
		'return_url'	=> '',
		'user_account'	=>	$user_account,
		'out_trade_no'	=> time(),
		'payment_type'	=> $payment_type,
		'total_fee'		=> $total_fee,
		'trade_time'	=> date('Y-m-d H:i:s', time()),
		'body'			=> $user_name,
	);

	$params['sign'] = _make_sign($params, $key);

	function _make_sign($data, $key)
    {
    	
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
    	 
    	return $result;
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

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="<?php echo $url;?>" id="payform" method="post">
		<?php 
			foreach($params as $k=>$v)
			{
				echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
			}
		?>	
	</form>
	<script>
		document.getElementById('payform').submit();
	</script>
</body>
</html>	