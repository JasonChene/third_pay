<?php
/**
 * 2017-11-27
 * 验签类
 */
class	encrypt{
	
	/**
	 *  签名sign
	 */
	 function  sign($data){
	
		$rsaPrivateKeyFilePath = null;
		//私钥参数
		$priKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCEa3WZDI0l1MjSaDdCkaWWV7FRtVkXqBLfqgCCo3/lP8JG5BLyyMfWhmPXGO/RRQxSQZ0lQ58aEY/PD4sSQZV56tzwgG24kPJ0yEga9ISX7upbWISDjfWcMRFcmtXijn+Dm5sxKRcxS+5bANYbJ0bqhqlAxGAZYEHQ2SJiDpB4ZqnklXfOsTUiQk/eGV5jeyY7+goDcG59SfBwejsUk0gb7QpLRhOlwrT+974DPBZd95/Y6lj0Ga6O/9igtmF0sllDh/mhjG4UbdZ2NODNrBIkMJ3ufQmMnn/g4fIvoaWTFVQ6ZFOrzIDkJolCE63MDJOLW5YfRvzYgvvsCqPTIZqPAgMBAAECggEAN//FvJKdQklpH+norKMxVpIBL9+0LJfsW1/mVVjVZlp/S0F6befQ8vzkTfuCt4ouur56uV6OcxTILzWQh4jqsKNNCfwV6n6Jui9UpumPBT2JKFYXvBaVrpblk7b7CpNeg4aTq8eHwlYtF5kT8mm6yMVn4DbruO+HBc49hyBHolKPg2g9KS2ccbl7b5l2DWY0C29/Vpe7DfFRNUv9UEKMtyqfJvMgZSuuYxwP4hdv/wfwCPoGxy6wuWXUreATff4dCHEuCauW/FRjfiZf+ZAjmPa4xb0RSqGVuURrTBJmckx9w7pEcjYKfdRv0ObAW2e9bNsEZVyZG3GLJncdXdMYSQKBgQDs8cHjQwTeOH6MUOBgjgpi3WL60TnoounhpJzyGXPJWMvg/kCrL090WxoUQWA/P/WZlP3Gxq1VeTuX8ZiWT9hnADIuzSAkRnpzM0Y5CGlNWKM1oRXFGS/cfGsS0iqQDqkndJu91EkeVkqwDNOCac4f+LOXY0VYSpkbBNcmhgNwewKBgQCPEbxAfHZRBrH9msrkJbThilvOkJz7RnPVIlQ2pLj5rhD8EAcH0uIGcyDChRh6xZfYkkCSy1w4pPE570JuzWG8xsFjYyxRHu+quz5nxy5YMjxbNZ6ylb+Rh5mgK80hR1I6WUlyhlzetUqqoe+MMaqUc13pues/jtcrGlWCqx4D/QKBgQCmmVW6dWT22sIf5r3dJgIngYIW2QXejK3l+dhgYYUIDWdMy3is5Jb5ORUdKNqDnnURh7DoEsnNT3CXZUfkaD/ALGMchR4UY3m1hlfZwhBIUgpvqtOjwhHk9ZZTpXJH2AcXtuXKk3jV29an8lzFVZ+Y20VrLGGVst30IXMloxTIAQKBgEYm1R7tqHpMhSQsLYY3Mv1QgXnFESRoxE1i2tY8aPMMtglSto/QiMHO2+Zlqr4weydXd1BDZQHlZt8YgYOhM2aEMrlQHJ/eQnh9/biXQxM99rhmj11T7i4mxl5ye8/wj9JKi6gbWBhf3q/SXYpppBHMi+UaI87zdHAPJpS+4SXlAoGAZ5C+LqN9l1kmfKdGRAYtBV4sskg6sjP9tl7EHz194hFaPcZ2IEKesKdl1roJW78QCSHSRvUvF6iy9Hfj//KHKouGXSm/smmu0nMpCWefM79/eLBM8X90WdVVpugNI1rS8MZd4vIVxYIcUA39z9GfpIASAbuL6zLCqnIXOVtHYUM=';
		//首先判断下私钥是否为文件，不为文件的话要加上私钥的头部和尾部
		if (file_exists($rsaPrivateKeyFilePath)) {
			//读取公钥文件
			$priKey = file_get_contents($rsaPrivateKeyFilePath);
			//转换为openssl格式密钥
			$res = openssl_get_privatekey($priKey);
		} else {
				
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
					wordwrap($priKey, 64, "\n", true) .
					"\n-----END RSA PRIVATE KEY-----";
		}
		//判断私钥文件是否可用
		$piKey = openssl_pkey_get_private($res);
	
		if ($piKey) {
			$res = openssl_get_privatekey($res);
			openssl_sign($data, $sign, $res, 'SHA256');
			$sign = base64_encode($sign);
			return $sign;
		}
	}
	
	
	/**
	 * 16进制编码转字符串
	 */
    function Hex2String($hex){
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	/**
	 * 字符串转16进制编码
	 */
	function String2Hex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	
	/**
	 * 随机字符串
	 */
	function  nonceStr($len = 12)
	{
		$arr = array(
				'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
				'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
				'U', 'V', 'W', 'X', 'Y', 'Z',
		);
		$str = '';
		for ($i = 1; $i <= $len; $i++) {
			$str .= $arr[mt_rand(0, 35)];
		}
		return $str;
	}
	
	/**
	 * 对数组排序
	 * @param array $para 排序前的数组
	 * return array 排序后的数组
	 */
	function argSort(array $para)
	{
		ksort($para);
		reset($para);
		return $para;
	}
	
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 *
	 * @param array $para 需要拼接的数组
	 * return String 拼接完成以后的字符串
	 */
	function createLinkstring(array $para)
	{
		$arg = '';
		foreach ($para as $key => $val){
			$arg .= ($key . '=' . $val . '&');
		}
		//去掉最后一个&字符
		$arg = substr($arg, 0, -1);
	
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()) $arg = stripslashes($arg);
		return $arg;
	}
	
	/**
	 * 签名字符串，以&符号拼接密钥
	 *
	 * @param String $prestr 需要签名的字符串
	 * @param String $key 私钥
	 * return 签名结果
	 */
	function md5Sign($prestr,$key)
	{
		$prestr = $prestr  . '&key=' . $key;
		return md5($prestr);
	}
	
	
	/**
	 * 请求数据方法
	 */
	function postJSON($url, $params = NULL, $timeout = 8)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);		// 让cURL自己判断使用哪个版本
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);		// 在HTTP请求中包含一个"User-Agent: "头的字符串。
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);					// 在发起连接前等待的时间，如果设置为0，则无限等待
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);							// 设置cURL允许执行的最长秒数
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);						// 返回原生的（Raw）输出
		curl_setopt($curl, CURLOPT_ENCODING, FALSE);							// HTTP请求头中"Accept-Encoding: "的值。支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，请求头会发送所有支持的编码类型。
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);							// 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);							// 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_HEADER, FALSE);								// 启用时会将头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_POST, TRUE);
		if (!empty($params) && is_array($params) && count($params) >= 1) {
	
			$jsonStr = json_encode($params);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8',
			'Content-Length: ' . strlen($jsonStr),
			));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonStr);
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
}
?>