<?php
class lib{
    //加密数据
    public static function SignCrypt($value, $mKey) {
        ksort($value);
        $Data = '';
		foreach($value as $x=>$x_value) {
			$Data .= "$x=$x_value&";
		}
		if(substr($Data, -1) == '&') {
			$Data = substr($Data, 0, -1);
        }
		$Private_Key = openssl_get_privatekey($mKey);
		openssl_sign($Data, $Sign, $Private_Key, OPENSSL_ALGO_MD5);
		$Sign = base64_encode($Sign);
		return $Sign;
    }
    //CURL提交表单
    public static function HTTP_CURL_DATA($url, $data, $second = 30) {
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $str = curl_exec($ch);
        if(!$str) {
            $str = curl_error($ch);
        }
        curl_close($ch);
        return $str;
    }
    //校验数据
    public static function VerifyCrypt($value, $value2, $mKey) {
        ksort($value);
        $SignData = '';
		foreach($value as $x => $x_value) {
			if($x == 'Sign') {
				continue;
			}
			$SignData .= "$x=$x_value&";
		}
		if(substr($SignData, -1) == '&') {
			$SignData = substr($SignData, 0, -1);
        }
		$PublicKey = openssl_get_publickey($mKey);
		if(openssl_verify($SignData, $value2, $PublicKey, OPENSSL_ALGO_MD5)) {
			return true;
		}
		return;
    }
    public static function myksort($value) {
        $Data = array();
        foreach($value as $x => $x_value) {
            $Data[$x] = $x_value;
        }
        return $Data;
    }
}