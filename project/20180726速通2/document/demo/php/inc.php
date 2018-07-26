<?php
header('Content-Type:text/html;charset=utf8');
date_default_timezone_set('Asia/Shanghai');
    /*
    * 生成签名，$args为请求参数，$key为私钥
    */
	function makeSignature($args, $key){
		if(isset($args['sign'])) {
			$oldSign = $args['sign'];
			unset($args['sign']);
		} else {
			$oldSign = '';
		}

        ksort($args);
        $requestString = '';
        foreach($args as $k => $v) {
            $requestString .= $k . '='.($v);
            $requestString .= '&';
        }
        $requestString = substr($requestString,0,strlen($requestString)-1);
        $newSign = md5( $requestString.$key);
        return $newSign;
    }
	/*
    * 生成签名，签名转换
    */
	function arrayToKeyValueString($param){
		$str = '';
		foreach($param as $key => $value) {
			$str = $str . $key .'=' . $value . '&';
		}
		return $str;
    }
    
?>
