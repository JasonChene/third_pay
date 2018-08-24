<?php
class COMMON
{
	
	function RandStr($length = 8 ) 
	{ 
		$str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
		str_shuffle($str);
		return substr(str_shuffle($str),26, $length);
	}

	function ParameSort($arrayData){
		$str = '';
		if($arrayData === null){
			return $str;
		}
		
		//对数组单元按照键名从低到高进行排序
		ksort($arrayData);
		foreach ($arrayData as $key => $value) 
		{ 
		    if(!empty($value))
			{
			  $str.= $key.$value;
			}
		}
	
		return $str;
	}
	
	/**
	 * 发送post请求
	 * @param string $url 请求地址
	 * @param array $post_data post键值对数据
	 * @return string
	 */
	function send_post($url,$key,$timestamp,$nonce,$signature, $post_data) {
	 
		//$postdata = http_build_query($post_data);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' =>array('Content-type:application/x-www-form-urlencoded','key:'.$key,'timestamp:'.$timestamp,'nonce:'.$nonce,'signature:'.$signature,"signtype:RSA"), 
				'content' => $post_data,
				'timeout' => 15 * 60 // 超时时间（单位:s）
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
	 
		return $result;
	}
	
	function send_post_md5($url,$key,$timestamp,$nonce,$signature, $post_data) {
	 
		//$postdata = http_build_query($post_data);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' =>array('Content-type:application/x-www-form-urlencoded','key:'.$key,'timestamp:'.$timestamp,'nonce:'.$nonce,'signature:'.$signature,"signtype:MD5"), 
				'content' => $post_data,
				'timeout' => 15 * 60 // 超时时间（单位:s）
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
	 
		return $result;
	}
}
?>