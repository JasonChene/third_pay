<?php
/*
 * PHP 异步回调DEMO
 */
$merchant_private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQABAoGADlxB583FmwLLvyqazM3gI2vYk5gle6mhTdMZ32sC7ERarb6WYnEJjXMURExxBkX0XG7FBYPXjTPCXpBam7lw7dpgR9BhFm09+FLqPlirr64HQlAwQwDyFmQJuGPq5ASzl7e+fIM8qAqWEH6HuEtFSljmebHo2+6OwLxzNcivGTECQQDrq2AQf1moG4Fs1aNNNvETNL5b8doCIjEaZV26V0bNdHKemxjPbhuxENx6bqnIAEaDl5OrajXOgI3WPz8+M2cTAkEA1BnJ2mizVP/Jn+jArwgLfCJYHR/5u589zkGsLly2ugdf3nFZi6pOHWE460AbPzWXXRMUpoJEl+bF6DEUk/wYXwJAb+y7OfqRhRJTHHI2FVTTl5CEG7y4Ei1U7rlXk0kh+i+kxAja9qDPi/97BraJ8c+XraWOX2mY1lMdibQOACd/ewJBAJNCrHEmDIzRY23RLibYUREIz2C5WKy5rTHNSvyNhpi2kgtha6iav82KOPis8735OXR30PiirXlB0tqZaQ4uE8UCQFUlhs1Av7nZAlPOWxOwUxPyYqebWKoi0FFhvYqrd49BHth8bcA1dFJXu0dAIHYnWbxKDBcoERvt61si4ALG+V4=
-----END RSA PRIVATE KEY-----';
/*商户自己生成的私钥*/

$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDQakcqdInHJ1Q2u0Pi2qBYU0WxTWG24Kga3uQ78QNIiXtXy8BdNid4exr9hXQW34byNc5nor/HRUn31hh8PNvVd8y4B6WJDfYKY+Bq5+rSInhi0O1o0Ht2myjYi9rV9/oVdzfOIdF3MqgKEvrNxhsHyuJ9dteHQoGXtWSRnEIDQIDAQAB
-----END PUBLIC KEY-----';
/*商户自己生成的公钥*/

$bfb_pay_key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCOAoslcPOFmqk/Okv5sT3z+TsnwjCXtev4OPTM9oLQpr7DwHNYlXIxGkI0rf0RWW6zKMXvrNCYXBjanUYvi0ukM0ujLJiZ+qMutRzxkckqN1ZXSRsjPoCG7S46M1Ew52TKYYkPm/53gqe+gQzdIEDAg8cuxIbSiuKGr2em/jnRfQIDAQAB
-----END PUBLIC KEY-----';
/*由BFB提供的公钥*/
   

    $notify_data = json_decode(file_get_contents('php://input'), true);//取POST过来的JSON数据，普通的$_POST无法取值

	mylog('收到BFB通知啦');
	
    $notify = $notify_data['context'];//提取密文

    $mer_private_key = openssl_pkey_get_private($merchant_private_key);//取私钥资源号

    $bfb_public_key = openssl_pkey_get_public($bfb_pay_key);//取PAY公钥资源号

    $data = rsa_decrypt($notify, $mer_private_key);//执行解密流程

    $context_arr = json_decode($data, true);//转为数组格式

    $sign = $context_arr['businessHead']['sign'];//取SIGN

    $businessContext = $context_arr['businessContext'];//取businessContext

    ksort($businessContext);//按ASCII码从小到大排序

    $json_businessContext = json_encode($businessContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $isVerify = (boolean) openssl_verify($json_businessContext, base64_decode($sign), $bfb_public_key, OPENSSL_ALGO_MD5);
    
	mylog('收到BFB通知验签结果' . $isVerify);
    if ($isVerify) {
        /**
         * 验签成功，执行商户自己的逻辑
         */
        echo 'SUC';  //成功返回SUC，系统则不会继续推送notify
    }else{
        echo 'FAIL';

    }

    /**
     * RSA解密
     * @param $encrypted
     * @param $rsa_private_key
     * @return string
     */
    function rsa_decrypt($encrypted, $rsa_private_key){
        $crypto = '';
        $encrypted = base64_decode($encrypted);
        foreach (str_split($encrypted, 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $rsa_private_key);
            $crypto .= $decryptData;
        }
        return $crypto;
    }
	
	
	// 记录日志
	function mylog($str) {
		if ( is_array($str) || is_object($str) ) {
			$str = var_export($str, true);
		}

		$str = date('Y-m-d H:i:s') . "\t" . $str . "\r\n";

		$r = @file_put_contents('log.txt', $str, FILE_APPEND);
		if ( !$r ) {
			@unlink('log.txt');
			@file_put_contents('log.txt', $str);
		}
	}

	 