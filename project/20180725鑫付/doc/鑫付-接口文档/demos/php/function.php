<?php
/**
 * 发送HTTP请求方法
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @param  array $header header头信息
 * @param  int $timeout 超时时间
 * @return array  $data   响应数据
 */
function http($url, $params, $method = 'GET', $header = array(), $timeout = 5){
	$header_arr = array();
    foreach($header as $k => $v){
		$header_arr[] = $k.':'.$v;
		}
	$opts = array(
		CURLOPT_TIMEOUT        => $timeout,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_HTTPHEADER     => $header_arr,
	 );
	//根据请求类型设置特定参数
	switch(strtoupper($method)){
		case 'GET':
			$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
			break;
		case 'POST':
			$params = http_build_query($params);
			$opts[CURLOPT_URL] = $url;
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $params;
			break;
		default:
			$return_data['http_data'] = '';
			$return_data['http_code'] = 405;
			$return_data['http_error'] = 'Method Not Allowed';
	}
	//初始化并执行curl请求
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$return_data['http_data'] = curl_exec($ch);
	$return_data['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$return_data['http_error'] = curl_error($ch);
	curl_close($ch);
	return  $return_data;

}

//调用接口前，先获取mchid
function get_mchid(){
	return 135893;
}

/**
 *  
 * @param  array  $param_arr 要做md5校验的参数数组
 * @param  string $key	厂商分配的key
 * @return string  md5校验值
 */
function get_md5($param_arr, $key){
	ksort($param_arr);
	$str = '';
	foreach($param_arr as $k => $v){
		$str .= $k.'='.$v.'&'; 
	}
	$str .= 'key='.$key;
	return strtoupper(md5($str));
}
