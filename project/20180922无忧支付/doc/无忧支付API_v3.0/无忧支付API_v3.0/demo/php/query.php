<?php
	header("Content-type: text/html; charset=utf-8"); 

	$url = 'https://www.wuyoupay.cn/query.php';
	$user_account = '';		//商户在无忧的账号
	$key = '';				//密钥

	$params = array(
		'user_account'	=>	$user_account,
		'out_trade_no'	=> '',
		'trade_no'		=> ''
	);

	$params['sign'] = _make_sign($params, $key);

	$result = _send_https_request($url.'?'. _to_url_params($params));

	echo $result;

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
    		if($v != "" && !is_array($v)){
    			$buff .= $k . "=" . $v . "&";
    		}
    	}
    
    	$buff = trim($buff, "&");
    	return $buff;
    }

	function _send_https_request($url, $post_data=false){
    	$curl = curl_init();
    	curl_setopt ($curl, CURLOPT_URL, $url);
    	curl_setopt ($curl, CURLOPT_HEADER,0);
    	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    	if($post_data){
    		curl_setopt ($curl, CURLOPT_POST, 1);
    		curl_setopt ($curl, CURLOPT_POSTFIELDS, $post_data);
    	}
    	curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt ($curl, CURLOPT_TIMEOUT,5);
    	$get_content = curl_exec($curl);
    	curl_close ($curl);
    	return $get_content;
    }
    
?>
