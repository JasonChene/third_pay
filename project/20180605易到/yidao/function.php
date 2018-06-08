<?php
function sign($arr,$aeskey,$md5key,$pay_mid)
    {
        $str = sortData($arr);
        $baseStr = base64_encode($str);
        $aesPrivage = encrypt($baseStr, $aeskey,'AES-128-ECB');
        $aesPrivage = strtoupper($aesPrivage);
        $sign = strtoupper(md5($aesPrivage . $md5key));
        $arr['sign'] = $sign;
        $str2 = sortData($arr);
        $baseStr2 = base64_encode($str2);
        $transData = encrypt($baseStr2, $aeskey,'AES-128-ECB');
        $data = array();
        $data['merchantCode'] = $pay_mid;
        $data['transData'] = $transData;
        $reqStr = "reqJson=" . json_encode($data);
        return $reqStr;
    }

    function sortData($arr)
    {
        array_walk($arr, function (&$v) {
            if (is_array($v)) {
                array_walk_recursive($v, function (&$v1) {
                    if (is_object($v1)) {
                        $v1 = get_object_vars($v1);
                        ksort($v1);
                    }
                });
                ksort($v);
            }
        });

        ksort($arr);
        key($arr);
        $str = "";
        foreach (array_keys($arr) as $key) {
            $str .= $key . "=" . $arr[$key] . "&";
        }
        $str = rtrim($str, "&");
        $str = str_replace(" ", "", $str);
        return $str;
    }
    function encrypt(string $data, string $key, string $method){
        $ivSize = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);

        // For storage/transmission, we simply concatenate the IV and cipher text
        // $encrypted = base64_encode($iv . $encrypted);
        $encrypted = bin2hex($encrypted);
        return $encrypted;
    }
    function decrypt(string $data, string $key, string $method){
        $data = hex2bin_d($data);
        $ivSize = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

        return $data;
    }
    function hex2bin_d($data) {
        $len = strlen($data);
        $newdata='';
        for($i=0;$i<$len;$i+=2) {
            $newdata .= pack("C",hexdec(substr($data,$i,2)));
        }
        return $newdata;
    }
    function pkcs5_pad($text, $blocksize) {

        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);

    }
    function VerifySign($str,$aeskey,$md5key){
        //解密
        $sec_dec = decrypt($str,$aeskey,'AES-128-ECB');
        $sec_dec = base64_decode($sec_dec);
        //分割
        $pra = explode("&",$sec_dec);
        $result_pra = array();//异步回调的参数
        foreach($pra as $thispra){
            $temp_pra = explode("=",$thispra);
            $result_pra[$temp_pra[0]] = $temp_pra[1];
        }
        $sign = $result_pra["sign"];
        //移除sign
        unset($result_pra["sign"]);
        $result_str = sortData($result_pra);
        $baseStr = base64_encode($result_str);
        $aesPrivage = encrypt($baseStr, $aeskey,'AES-128-ECB');
        $aesPrivage = strtoupper($aesPrivage);
        $sign2 = strtoupper(md5($aesPrivage . $md5key));
        if ($sign==$sign2){
            return $result_pra;
        }else{
            return false;
        }

    }
    function QRcodeUrl($code){
    if(strstr($code,"&")){
      $code2=str_replace("&", "aabbcc", $code);//有&换成aabbcc
    }else{
      $code2=$code;
    }
    return $code2;
  }
    ?>
